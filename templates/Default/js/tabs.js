(function (document){

    var currentTab = null

    document.addEventListener("DOMContentLoaded", start)
    
    function start(){

        var url_param = (window.location.hash).slice(1);
        const tabs = document.querySelectorAll('[data-tab]');

        for(var i = 0; i < tabs.length; i++){
            tabs[i].addEventListener('click', clickTab.bind(tabs[i]));

            if ((url_param == "" || typeof url_param === "undefined") && i == 0 || tabs[i].dataset.tab === url_param){
                currentTab = tabs[i]
                tabs[i].classList.add('active');
                continue;
            }    

            hideTab(tabs[i].dataset.tab);    
        }
    }

    function clickTab(){
        currentTab.classList.remove('active');
        this.classList.add('active');

        hideTab(currentTab.dataset.tab);

        currentTab = this;
        showTab(currentTab.dataset.tab);

        var param = '#' + currentTab.dataset.tab,
            url = window.location.pathname;

        window.history.replaceState(null, null, url + param);
    }

    function showTab(el){
        document.querySelector('[data-tab-content="' + el + '"]').style.display = 'block'
    }

    function hideTab(el){
        document.querySelector('[data-tab-content="' + el + '"]').style.display = 'none'
    }

})(document);
