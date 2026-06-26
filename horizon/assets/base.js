export function debounce(fn, delay = 300) {
  let timer;
  return (...args) => {
    clearTimeout(timer);
    timer = setTimeout(() => fn(...args), delay);
  };
}

export function throttle(fn, limit = 100) {
  let inThrottle;
  return (...args) => {
    if (!inThrottle) {
      fn(...args);
      inThrottle = true;
      setTimeout(() => (inThrottle = false), limit);
    }
  };
}

export function onAnimationEnd(el, callback) {
  let called = false;
  const done = () => {
    if (!called) {
      called = true;
      callback();
    }
  };
  el.addEventListener('animationend', done, { once: true });
  el.addEventListener('transitionend', done, { once: true });
  requestAnimationFrame(() => requestAnimationFrame(done));
}

export function partition(arr, predicate) {
  const pass = [];
  const fail = [];
  for (const item of arr) {
    (predicate(item) ? pass : fail).push(item);
  }
  return [pass, fail];
}
