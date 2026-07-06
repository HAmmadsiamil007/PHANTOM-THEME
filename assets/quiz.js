class QuizRecommendation extends HTMLElement {
  constructor() {
    super();
    this.currentIndex = 0;
    this.accumulatedHandles = [];
    this.productsToShow = parseInt(this.getAttribute('data-products-to-show'), 10) || 3;
    this.questionEls = [];
    this.optionButtons = [];
    this.boundClick = this.handleOptionClick.bind(this);
    this.boundAddToCart = this.handleAddToCart.bind(this);
  }

  connectedCallback() {
    this.intro = this.querySelector('[data-quiz-intro]');
    this.results = this.querySelector('[data-quiz-results]');
    this.cardsContainer = this.querySelector('[data-quiz-cards]');
    this.noResults = this.querySelector('[data-quiz-no-results]');
    this.startBtn = this.querySelector('[data-quiz-start]');
    this.resetBtn = this.querySelector('[data-quiz-reset]');
    this.questionEls = [...this.querySelectorAll('[data-quiz-question]')];
    this.optionButtons = [...this.querySelectorAll('[data-quiz-option]')];
    this.cardSlots = [...this.querySelectorAll('[data-quiz-card-slot]')];

    this.startBtn?.addEventListener('click', () => this.start());
    this.resetBtn?.addEventListener('click', () => this.reset());
    this.optionButtons.forEach((btn) => btn.addEventListener('click', this.boundClick));

    if (window.Shopify && window.Shopify.designMode) {
      window.addEventListener('shopify:section:select', () => this.reset());
      window.addEventListener('shopify:block:select', () => this.reset());
    }
  }

  disconnectedCallback() {
    this.optionButtons.forEach((btn) => btn.removeEventListener('click', this.boundClick));
    this.startBtn?.removeEventListener('click', this.start);
    this.resetBtn?.removeEventListener('click', this.reset);
    document.querySelectorAll('[data-quiz-add-to-cart]').forEach((btn) => {
      btn.removeEventListener('click', this.boundAddToCart);
    });
  }

  start() {
    this.intro?.classList.add('is-hidden');
    this.currentIndex = 0;
    this.showQuestion(0);
  }

  showQuestion(index) {
    this.questionEls.forEach((el, i) => {
      el.classList.add('is-hidden');
      el.classList.remove('is-exiting');
    });
    if (this.questionEls[index]) {
      this.questionEls[index].classList.remove('is-hidden');
    }
  }

  handleOptionClick(e) {
    const btn = e.currentTarget;
    const handles = (btn.getAttribute('data-product-handles') || '')
      .split(',')
      .map((h) => h.trim())
      .filter(Boolean);

    this.accumulatedHandles.push(...handles);
    btn.classList.add('is-loading');

    setTimeout(() => {
      btn.classList.remove('is-loading');
      const isLast = btn.hasAttribute('data-last-question');

      if (this.questionEls[this.currentIndex]) {
        this.questionEls[this.currentIndex].classList.add('is-exiting');
      }

      setTimeout(() => {
        this.questionEls.forEach((el) => el.classList.add('is-hidden'));

        if (isLast) {
          this.showResults();
        } else {
          this.currentIndex++;
          this.showQuestion(this.currentIndex);
        }
      }, 300);
    }, 400);
  }

  showResults() {
    this.questionEls.forEach((el) => el.classList.add('is-hidden'));
    this.results?.classList.remove('is-hidden');

    if (this.accumulatedHandles.length === 0) {
      this.noResults?.classList.remove('is-hidden');
      return;
    }

    this.noResults?.classList.add('is-hidden');
    this.cardSlots.forEach((slot) => slot.classList.add('is-loading'));

    const sorted = this.sortByFrequency(this.accumulatedHandles);
    const handles = sorted.slice(0, this.productsToShow);

    handles.forEach((handle, i) => {
      if (this.cardSlots[i]) {
        this.fetchProductCard(handle, this.cardSlots[i]);
      }
    });
  }

  sortByFrequency(handles) {
    const freq = {};
    handles.forEach((h) => (freq[h] = (freq[h] || 0) + 1));
    return [...new Set(handles)].sort((a, b) => freq[b] - freq[a]);
  }

  async fetchProductCard(handle, slot) {
    try {
      const res = await fetch(`/products/${handle}?section_id=quiz-product-card`);
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      const html = await res.text();
      slot.innerHTML = html;
      slot.classList.remove('is-loading');

      const form = slot.querySelector('.quiz-card__form');
      if (form) {
        form.addEventListener('submit', async (e) => {
          e.preventDefault();
          const btn = form.querySelector('[data-quiz-add-to-cart]');
          if (btn) btn.disabled = true;
          try {
            const fd = new FormData(form);
            await fetch('/cart/add.js', {
              method: 'POST',
              body: fd,
              headers: { Accept: 'application/json' },
            });
            if (btn) {
              btn.textContent = 'Added!';
              setTimeout(() => {
                btn.textContent = 'Add to cart';
                btn.disabled = false;
              }, 2000);
            }
          } catch (err) {
            if (btn) btn.disabled = false;
          }
        });
      }
    } catch (err) {
      slot.classList.remove('is-loading');
      slot.innerHTML = '<p>Could not load product.</p>';
    }
  }

  reset() {
    this.accumulatedHandles = [];
    this.currentIndex = 0;
    this.results?.classList.add('is-hidden');
    this.cardSlots.forEach((slot) => {
      slot.innerHTML = '';
      slot.classList.remove('is-loading');
    });
    this.intro?.classList.remove('is-hidden');
    this.questionEls.forEach((el) => {
      el.classList.add('is-hidden');
      el.classList.remove('is-exiting');
    });
    this.optionButtons.forEach((btn) => btn.classList.remove('is-loading'));
    this.noResults?.classList.add('is-hidden');
  }
}

customElements.define('quiz-recommendation', QuizRecommendation);
