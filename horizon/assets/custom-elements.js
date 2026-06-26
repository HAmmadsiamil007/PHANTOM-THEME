import { BaseElement, DeferrableElement } from '@theme/component-base';

class ThemeInterface extends BaseElement {
  cacheElements() {
    this.cache.headerGroup = document.getElementById('header-group');
    this.cache.footerGroup = document.getElementById('footer-group');
  }

  bindEvents() {
    document.addEventListener('shopify:section:load', (e) => {
      const container = e.detail.container;
      this.handleSectionLoad(container);
    });
  }

  handleSectionLoad(container) {
    if (container.closest('#header-group')) {
      this.cache.headerGroup = document.getElementById('header-group');
    }
    if (container.closest('#footer-group')) {
      this.cache.footerGroup = document.getElementById('footer-group');
    }
  }
}

customElements.define('theme-interface', ThemeInterface);
