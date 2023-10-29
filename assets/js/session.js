export function setSessionConfig(){
    let element = document.querySelector(".tasks-container");
    if(element == null){
        return;
    }
    let config = element.getAttribute("data-config");

    if(sessionStorage.getItem("config") != config || sessionStorage.getItem("config") == null){
        sessionStorage.setItem("config", config);
    }
}
