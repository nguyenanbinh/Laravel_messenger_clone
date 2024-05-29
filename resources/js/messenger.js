let searchPage = 1;
let noMoreDataSearch = false;
let tempSearchValue = "";
let setSearchLoading = false;

function imageReview(input, selector) {
    if(input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $(selector).attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function searchUsers(query) {
    if(query !== tempSearchValue) {
        searchPage = 1;
        noMoreDataSearch = false;
    }
    tempSearchValue = query;

    if ( !noMoreDataSearch && !setSearchLoading) {
        $.ajax({
            type: "GET",
            url: "/messenger/search",
            data: {query, page: searchPage},
            beforeSend: function () {
                setSearchLoading = true;
                let loader = `
                <div class="text-center search-loader">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                `;

                $('.user_search_list_result').append(loader);
            },
            success: function (response) {
                setSearchLoading = false;
                $('.user_search_list_result').find('.search-loader').remove();
                if(searchPage < 2) {
                    $('.user_search_list_result').html(response.records);
                } else {
                    $('.user_search_list_result').append(response.records);
                }

                noMoreDataSearch = searchPage >= response?.last_page;

                if(!noMoreDataSearch) searchPage++;
            },
            error: function (xhr, status, error) {
                setSearchLoading = false;
                $('.user_search_list_result').find('.search-loader').remove();
                console.log(xhr.responseJSON.errors);
            }
        });
    }
}

/**
 * Debounce a function. It will wait a certain amount of time before invoking
 * the function. Useful for preventing multiple form submissions or API calls
 * from being made quickly in succession.
 * @param {Function} fn The function to debounce
 * @param {Number} ms The amount of time to wait before invoking the function
 * @returns {Function} The debounced function
 */
function debounce(fn, ms) {
    let timer;

    /**
     * Returns a new function that will be used as the debounced version of fn
     * @param {...*} args The arguments to pass to the debounced function
     */
    return function(...args) {
        const context = this;

        // If a timer is already active, clear it
        if(timer) clearTimeout(timer);

        // Set a new timer
        timer = setTimeout(() => {
            // Invoke the function with the passed in arguments
            fn.apply(context, args);
        }, ms);
    }
}

function actionOnScroll(selector, callback, topScroll = false) {
    $(selector).scroll(function () {
        let element = $(this).get(0);
        const condition = topScroll ? element.scrollTop === 0 :
         element.scrollTop + element.clientHeight >= element.scrollHeight;

        if(condition) callback();
    });
}



$(document).ready(function () {
    const debouncedSearch = debounce(function () {
        const value = $('.user_search').val();
        searchUsers(value);
    }, 1500);

    $('#select_file').change(function (e) {
        imageReview(this, '.profile-image-preview');
    });

    $('.user_search').on('keyup', function () {
        let query = $(this).val();
        if(query.length > 0) {
            debouncedSearch();
        }
    });

    actionOnScroll('.user_search_list_result', function () {
        let value = $('.user_search').val();
        searchUsers(value);
     })

});


