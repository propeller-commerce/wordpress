(function ($, window, document) {

    const {__, _x, _n, _nx} = wp.i18n;

    Propeller.Search = {
        init: function () {
            $('form[name="search"]').off('submit').on('submit', this.doSearch);
            this.initAutocomplete();
            this.initSubmitEvents();
        },
        initSubmitEvents: function() {
            $(document).on('keydown', 'form[name="search"] input[name="term"]:first', function(event){
                if(event.keyCode == 13) {
                    if($(this).val().length == 0) {
                        event.preventDefault();
                        return false;
                    }
                    window.location.href = PropellerHelper.urls.search + $(this).val() + '/';
                }
            });
            $(document).on('click', '.btn-search', function(e){
                e.preventDefault();
                window.location.href = PropellerHelper.urls.search + $('form[name="search"] input[name="term"]:first').val() + '/';
            })
        },
        initAutocomplete: function() {
            if (!$('form[name="search"] input[name="term"]:first').length)
                return;
            let maxResults = 6;
            window.searchAutoComplete = new window.autoComplete({
                selector: "form[name=\"search\"] input[name=\"term\"]",
                threshold: 3,
                debounce: 300,
                cache: false,
                searchEngine: function(query, record) {
                    return 1;
                },
                data: {
                    keys: ['name'],
                    src: async (query) => {
                        let data = [];
                        await Propeller.Ajax.call({
                            url: PropellerHelper.ajax_url,
                            method: 'POST',
                            loading: $('form[name="search"]').find('.btn-search'),
                            data: {
                                action: 'global_search',
                                term: query,
                                offset: Propeller.Global.maxSuggestions
                            },
                            dataType: 'json',
                        }).then(function (response) {
                            if (response.hasOwnProperty('items')) {
                                for (let i in response.items) {
                                    data.push({
                                        name: response.items[i].name[0].value,
                                        slug: response.items[i].slug[0].value,
                                        image: response.items[i].image,
                                        url: response.items[i].url
                                    })
                                }
                            }
                        }).catch(function (args) {
                            console.log('error', args)
                        });
                        return data;
                    },
                },
                resultsList: {
                    tag: "ul",
                    class: "propeller-autosuggest-items",
                    maxResults: maxResults+1,
                    element: (list, data) => {
                        let searchUrl = PropellerHelper.urls.search + data.query
                        let li = document.createElement('li');
                        li.className = 'propeller-autosuggest-item';
                        let link = document.createElement('a');
                        link.href = searchUrl;
                        link.innerHTML = __('See All Result', 'propeller-ecommerce');
                        li.appendChild(link);
                        list.appendChild(li);
                    },
                },
                resultItem: {
                    tag: "li",
                    class: "propeller-autosuggest-item",
                    element: (item, data) => {
                        //Clear.
                        while (item.firstChild) {
                            item.removeChild(item.firstChild);
                        }
                        // Set img.
                        if(item.value.image !== 'all') {
                            let imgWrap = document.createElement('div');
                            let image = document.createElement('img');
                            imgWrap.className = 'autoComplete_item-img';
                            image.src = data.value.image;
                            image.width = image.height = 35;
                            imgWrap.appendChild(image);
                            item.appendChild(imgWrap);
                        }
                        // Set text.
                        let txtWrap = document.createElement('div');
                        txtWrap.className = 'autoComplete_item-name';
                        txtWrap.innerHTML = data.value.name;
                        item.appendChild(txtWrap);
                    },
                    highlight: "autoComplete_highlight",
                    selected: "autoComplete_selected"
                },
                events: {
                    input: {
                        selection: (event) => {
                            if(!event.detail.selection.hasOwnProperty('value')) {
                                return;
                            }
                            window.location.href = event.detail.selection.value.url;
                        }
                    }
                }
            });
        },
        doSearch: function(event) {
            event.preventDefault();
            event.stopPropagation();

            return false;
        }

    };

    //Propeller.Search.init();

}(window.jQuery, window, document));