function shop(i)
{
var evbody = document.getElementById('ev_shop_body');
evbody.innerHTML="<center><img src='/shop/style/loading.gif' width='140px'></center>"
       $.ajax({
                type: "POST",
                url: "/shop/shop.php",
                data:  {'id': idp , 'pass': pass, 'page' : i },
                success:  function(html) {
				evbody.innerHTML=html;
				}
});
}
function balance()
{
var bal = document.getElementById('balanceEp');
       $.ajax({
                type: "POST",
                url: "/shop/balance.php",
                data:  {'id': idp , 'pass': pass },
                success:  function(html) {
				bal.innerHTML=html;
				}
});
}

function basket(i)
{
var evbody = document.getElementById('ev_shop_body');
evbody.innerHTML="<center><img src='/shop/style/loading.gif' width='140px'></center>"
       $.ajax({
                type: "POST",
                url: "/shop/basket.php",
                data:  {'id': idp , 'pass': pass, 'page' : i },
                success:  function(html) {
				evbody.innerHTML=html;
				}
});
}

function buy()
{
var tbody = document.getElementById('bodystyle');
       $.ajax({
                type: "POST",
                url: "/shop/buy.php",
                data:  {'id': idp , 'pass': pass },
                success:  function(html) {
				tbody.style.display='none';
				alertify.success(html);
				}
});
}
function deletbask(id)
{
var tr = document.getElementById('indexbask_'+id);
       $.ajax({
                type: "POST",
                url: "/shop/delbasket.php",
                data:  {'id': idp , 'pass': pass, 'del' : id },
                success:  function(html) {
				tr.style.display='none';
				balance();
				}
});
}

function cart(id)
{
var ammount = document.getElementById('ammount_'+id).value;
       $.ajax({
                type: "POST",
                url: "/shop/cart.php",
                data:  {'id': idp , 'pass': pass , 'idp': id, 'ammount': ammount},
                success:  function(html) {
				alertify.log(html);
				balance();
				}
});
}
