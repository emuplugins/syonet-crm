document.addEventListener("DOMContentLoaded", function () {
    // Obtém a URL atual
    var currentUrl = window.location.href;


    var allowedPages = 
    [ 
    "edit.php?post_type=syonet_form",
    "edit-tags.php?taxonomy=syonet_event_group",
    "edit-tags.php?taxonomy=syonet_company",
    "edit.php?post_type=syonet_submissions",
    "term.php?taxonomy=syonet_company",
    "term.php?taxonomy=syonet_event_group",
    "term.php?taxonomy=syonet_event_type",
    ];

    if ( ! allowedPages.some(page => currentUrl.includes(page))) {
        return;
    }

    // Seleciona o menu principal
    var menuItem = document.querySelector("#toplevel_page_syonet-options");

    if (menuItem) {
        menuItem.classList.add("wp-has-current-submenu", "wp-menu-open");

        // Lista de páginas e suas respectivas classes de menu
        var pages = {
            "edit.php?post_type=syonet_form": 'a[href="edit.php?post_type=syonet_form"]',
            "edit-tags.php?taxonomy=syonet_event_group": 'a[href*="edit-tags.php?taxonomy=syonet_event_group"]',
            "edit-tags.php?taxonomy=syonet_company": 'a[href*="edit-tags.php?taxonomy=syonet_company"]',
            "term.php?taxonomy=syonet_company": 'a[href*="edit-tags.php?taxonomy=syonet_company"]',
            "edit.php?post_type=syonet_submissions": 'a[href*="edit.php?post_type=syonet_submissions"]',
        };

        // Percorre as páginas e aplica as classes corretas
        Object.keys(pages).forEach(function (key) {
            if (currentUrl.includes(key)) {
                var link = menuItem.querySelector(pages[key]);
                if (link) {
                    link.classList.add("current");
                    if (link.parentElement) {
                        link.parentElement.classList.add("current");
                    }
                }
            }
        });
    }
});
