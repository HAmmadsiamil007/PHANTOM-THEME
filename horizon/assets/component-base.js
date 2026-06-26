export class BaseElement extends HTMLElement {
  constructor() {
    super();
    this.cache = {};
  }

  connectedCallback() {
    if (this.isConnected) {
      this.init();
    }
  }

  init() {
    this.cacheElements();
    this.bindEvents();
  }

  cacheElements() {
    this.cache = {};
  }

  bindEvents() {}
}

export class DeferrableElement extends BaseElement {
  init() {
    requestIdleCallback(() => super.init(), { timeout: 500 });
  }
}
