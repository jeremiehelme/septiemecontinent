import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  static targets = [ "trigger", "content" ]

  connect() {
    this.triggerTarget.addEventListener('click', () => this.toggleContent());
    this.contentTarget.addEventListener('transitionend', () => {
      if (this.contentTarget.clientHeight > 0) {
        this.contentTarget.style.height = 'auto';
      }
    });
  }
  toggleContent() {
    if (this.contentTarget.clientHeight > 0) {
      this.hideContent();
    } else {
      this.showContent();
    }
  }
  showContent() {
    this.contentTarget.style.height = `${this.contentTarget.scrollHeight}px`;
  }

  hideContent() {
    this.contentTarget.style.height = `${this.contentTarget.scrollHeight}px`;

    // Force a repaint to reset the CSS transition
    getComputedStyle(this.contentTarget).height;

    this.contentTarget.style.height = '0';
  }
}

