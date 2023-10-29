import * as session from "./js/session.js"

/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉')

session.setSessionConfig()

let configSession = sessionStorage.getItem("config");
let tasks = document.querySelectorAll(".tasks-list .card");

for(let i = 0; i < tasks.length; i++){
    if(i > JSON.parse(configSession).numberOfResult - 1){
        tasks[i].style.display = "none";
    }
}