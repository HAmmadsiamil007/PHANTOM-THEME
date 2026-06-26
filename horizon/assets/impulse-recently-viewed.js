/**
 * Recently Viewed Products — client-side rendering module.
 *
 * Reads product handles from RecentlyViewed (localStorage),
 * fetches product data via Shopify's AJAX API (/products/{handle}.js),
 * and renders card HTML into the container.
 */
import { RecentlyViewed } from '@theme/recently-viewed-products';

class ImpulseRecentlyViewed {
  constructor(container) {
    this.container = container;
    this.currency = container.dataset.currency || 'USD';
    this.maxProducts = parseInt(container.dataset.productCount, 10) || 4;
    this.grid = container.querySelector('.impulse-recently-viewed__grid');
    this.loading = container.querySelector('.impulse-recently-viewed__loading');

    if (!this.grid) return;

    this.init();
  }

  async init() {
    const handles = RecentlyViewed.getHandles().slice(0, this.maxProducts);

    // Don't show section if no recently viewed products
    if (handles.length === 0) {
      this.container.style.display = 'none';
      return;
    }

    try {
      const products = await this.fetchProducts(handles);
      this.render(products);
    } catch (err) {
      console.warn('Recently viewed: failed to load products', err);
      this.container.style.display = 'none';
    }
  }

  async fetchProducts(handles) {
    const fetchPromises = handles.map((handle) =>
      fetch(`/products/${handle}.js`)
        .then((res) => {
          if (!res.ok) return null;
          return res.json();
        })
        .catch(() => null),
    );

    const results = await Promise.all(fetchPromises);
    return results.filter(Boolean);
  }

  render(products) {
    if (products.length === 0) {
      this.container.style.display = 'none';
      return;
    }

    if (this.loading) {
      this.loading.remove();
    }

    const cardsHtml = products
      .map((product) => {
        const image = product.featured_image
          ? `<div class="impulse-recently-viewed__image">
              <img
                src="${product.featured_image}"
                alt="${product.title}"
                loading="lazy"
                width="${product.featured_image_width || 300}"
                height="${product.featured_image_height || 300}"
              >
            </div>`
          : `<div class="impulse-recently-viewed__image impulse-recently-viewed__image--placeholder">
              <svg viewBox="0 0 300 300" xmlns="http://www.w3.org/2000/svg"><rect width="300" height="300" fill="#f4f4f4"/></svg>
            </div>`;

        const price = product.price_min
          ? `<span class="impulse-recently-viewed__price">${this.formatMoney(product.price_min)}</span>`
          : '';

        return `<a href="/products/${product.handle}" class="impulse-recently-viewed__card">
          ${image}
          <div class="impulse-recently-viewed__info">
            <h3 class="impulse-recently-viewed__title">${product.title}</h3>
            ${price}
          </div>
        </a>`;
      })
      .join('');

    this.grid.innerHTML = cardsHtml;
  }

  formatMoney(cents) {
    try {
      return new Intl.NumberFormat('en', {
        style: 'currency',
        currency: this.currency,
      }).format(cents / 100);
    } catch {
      return `$${(cents / 100).toFixed(2)}`;
    }
  }
}

// Auto-initialize on DOM content loaded
document.addEventListener('DOMContentLoaded', () => {
  const containers = document.querySelectorAll('[data-section-type="impulse-recently-viewed"]');
  containers.forEach((el) => {
    new ImpulseRecentlyViewed(el);
  });
});
