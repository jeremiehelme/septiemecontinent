import { Application } from "@hotwired/stimulus";

import MenuController from "./controllers/menu_controller";
import SelectNavController from "./controllers/selectnav_controller";
import LogoSliderController from "./controllers/logoslider_controller";
import ShowHideController from "./controllers/show_hide_controller";

window.Stimulus = Application.start();
Stimulus.register("menu", MenuController);
Stimulus.register("selectnav", SelectNavController);
Stimulus.register("logoslider", LogoSliderController);
Stimulus.register("showhide", ShowHideController);