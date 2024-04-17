import { Controller } from "@hotwired/stimulus";

export default class extends Controller {
  redirect(event) {
    let link = event.target.value;
    if (link && link != "") {
      window.location = event.target.value;
    }
  }
}
