import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  static values = {
    open: Boolean,
  };

  open(event) {
    this.openValue = true;
  }

  close(event) {
    this.openValue = false;
  }

  toggle(event) {
    this.openValue = !this.openValue;
  }

  blur (event) {
    if (event.target == event.currentTarget) {
      this.openValue = false;
    }
  }
}
 