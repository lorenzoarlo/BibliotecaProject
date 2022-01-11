document.currentScript.onload = () => {

    const metaViewport_tag = document.createElement("meta");
    metaViewport_tag.name = "viewport";
    metaViewport_tag.content = "width=device-width, initial-scale=1.0";
    document.head.appendChild(metaViewport_tag);

    const websiteIcon = document.createElement("link");
    websiteIcon.rel = "icon";
    websiteIcon.type = "image/png";
    websiteIcon.href = "../MEDIA/site-icon.png";
    document.head.appendChild(websiteIcon);


    const elencoStylesheets = [
        "../STYLES/main-style.css",
        "../STYLES/input-style.css",
        "../STYLES/Alert-style.css",
        "https://fonts.googleapis.com/css?family=Material+Icons",
        "https://fonts.googleapis.com/css?family=Poppins",
        "https://fonts.googleapis.com/css?family=Dancing+Script",
        "https://fonts.googleapis.com/css?family=Waiting+for+the+Sunrise",
        "https://fonts.googleapis.com/css?family=Open+Sans",
        "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css",
        "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css"
    ];
    
    
    const elencoScripts = [
        "../SCRIPTS/Utilities.js",
        "../SCRIPTS/Alert.js"
    ]; 

    elencoStylesheets.forEach(stylesheetURL => {
        let styleElement = document.createElement("link");
        styleElement.rel = "stylesheet";
        styleElement.href = stylesheetURL;
        document.head.appendChild(styleElement);
    });

    elencoScripts.forEach(scriptURL => {
        let scriptElement = document.createElement("script");
        scriptElement.src = scriptURL;
        document.head.appendChild(scriptElement);
    });
    
};