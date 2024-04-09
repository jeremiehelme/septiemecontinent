import { Application } from "@hotwired/stimulus";

import MenuController from "./controllers/menu_controller";

window.Stimulus = Application.start();
Stimulus.register("menu", MenuController);
