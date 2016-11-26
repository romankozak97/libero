$(document).ready(function(e){
    $('.range-from').draggable();
    $('.range-to').draggable();
});

function searchFocus()
{
    $('.search-input').attr('placeholder', '');
}

function searchBlur()
{
    $('.search-input').attr('placeholder', 'Search');
}

function cookiesPopupClose()
{
    $('.cookies-popup').fadeOut('fast');
}

function goto(path)
{
    switch (path)
    {
      case 'catalogue':
        window.location.href='/catalogue';
        break;
      case 'register':
        window.location.href='/auth/register';
        break;
      default:
        exit;
        break;
    }
}

function loginEmailFocus()
{
    $('label[for="login-email"]').addClass('lbl-red');
}

function loginEmailBlur()
{
    $('label[for="login-email"]').removeClass('lbl-red');
}

function loginPassFocus()
{
    $('label[for="login-pass"]').addClass('lbl-red');
}

function loginPassBlur()
{
    $('label[for="login-pass"]').removeClass('lbl-red');
}

function loginNameFocus()
{
    $('label[for="login-name"]').addClass('lbl-red');
}

function loginNameBlur()
{
    $('label[for="login-name"]').removeClass('lbl-red');
}

function appendParam(name, value)
{
    // if parameter name is not set in URL yet
    if (window.location.href.indexOf(name) === -1)
    {
        // if URL contains no parameters
        if (window.location.href.indexOf('?') === -1)
        {
            window.location.href += '?'+name+'='+value;
        }
        else {
            window.location.href += '&'+name+'='+value;
        }
    }
    else {
        //TODO: parse values with regex
        var valueStart = window.location.href.indexOf(name)+name.length+1;
        window.location.href = '?'+name+'='+value;
    }
}

/** AJAX methods **/
function changeStatus(id)
{
    var index = document.getElementById('status-select').selectedIndex;
    console.log(index);

    $.ajax({
        method: 'GET',
        url: '/admin/editstatus?id='+id+'&status='+index,
        success: function(result) {}
    });
}

function addToCart(id)
{
    $.ajax({
        method: 'GET',
        url: '/catalogue/addtocart?id='+id,
        success: function(count)
        {
            $('#cart-count').html(count);
        }
    });
}

function removeFromCart(id)
{
    $.ajax({
        method: 'GET',
        url: '/catalogue/removecart?id='+id,
        success: function(count)
        {
            $('#cart-count').html(count);
            window.location.reload();
        }
    });
}

function deleteProduct(id)
{
    $.ajax({
        method: 'GET',
        url: '/admin/deleteproduct/id/'+id,
        success: function(result)
        {
            window.location.reload();
        }
    });
}

function deleteCategory(id)
{
    $.ajax({
        method: 'GET',
        url: '/admin/deletecategory/id/'+id,
        success: function(result)
        {
            window.location.reload();
        }
    });
}

function deleteOrder(id)
{
    $.ajax({
        method: 'GET',
        url: '/admin/deleteorder/id/'+id,
        success: function(result)
        {
            window.location.reload();
        }
    });
}