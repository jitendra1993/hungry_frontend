<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'pages';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['admin'] = "admin/Admin";
$route['admin/login'] = "admin/Admin/login";
$route['admin/logout'] = "admin/Admin/logout";
$route['admin/dashboard'] = "admin/Dashboard/index";
$route['admin/profile'] = "admin/Admin/profile";

$route['admin/store-category/view/(:num)'] = "admin/StoreCategory/storeCategoryView/$1";
$route['admin/store-category/view'] = "admin/StoreCategory/storeCategoryView";
$route['admin/store-category/edit/(:any)'] = "admin/StoreCategory/storeCategoryAdd/$1";
$route['admin/store-category/add'] = "admin/StoreCategory/storeCategoryAdd";

$route['admin/store/view/(:num)'] = "admin/Store/view/$1";
$route['admin/store/view'] = "admin/Store/view";
$route['admin/store/edit/(:any)'] = "admin/Store/add/$1";
$route['admin/store/add'] = "admin/Store/add";

$route['admin/setting/store-info'] = "admin/Setting/merchantInfo";
$route['admin/setting/setting-view/(:num)'] = "admin/Setting/settingView/$1";
$route['admin/setting/setting-view'] = "admin/Setting/settingView";
$route['admin/setting/edit/(:any)'] = "admin/Setting/storeSetting/$1";
$route['admin/setting/mail-setting'] = "admin/Setting/mailSetting";
$route['admin/setting/sm-setting'] = "admin/Setting/smSetting";

$route['admin/banner/view/(:num)'] = "admin/Banner/bannerView/$1";
$route['admin/banner/view'] = "admin/Banner/bannerView";
$route['admin/banner/edit/(:any)'] = "admin/Banner/bannerAdd/$1";
$route['admin/banner/add'] = "admin/Banner/bannerAdd";

$route['admin/auth/change-password'] = "admin/Admin/changePassword";
$route['admin/auth/forgot-password'] = "admin/Admin/forgotPassword";
$route['admin/auth/reset-password/(:any)'] = "admin/Admin/resetPassword/$1";
$route['admin/auth/reset-password'] = "admin/Admin/resetPassword";

$route['admin/catalog/category/view/(:num)'] = "admin/Catalog/categoryView/$1";
$route['admin/catalog/category/view'] = "admin/Catalog/categoryView";
$route['admin/catalog/category/edit/(:any)'] = "admin/Catalog/categoryadd/$1";
$route['admin/catalog/category/add'] = "admin/Catalog/categoryadd";

$route['admin/catalog/sub-category/view/(:any)'] = "admin/Catalog/subCategoryView/$1";
$route['admin/catalog/sub-category/view'] = "admin/Catalog/subCategoryView";
$route['admin/catalog/sub-category/edit/(:any)'] = "admin/Catalog/subCategoryadd/$1";
$route['admin/catalog/sub-category/add'] = "admin/Catalog/subCategoryadd";

$route['admin/catalog/addon-category/view/(:num)'] = "admin/Catalog/addonCategoryView/$1";
$route['admin/catalog/addon-category/view'] = "admin/Catalog/addonCategoryView";
$route['admin/catalog/addon-category/edit/(:any)'] = "admin/Catalog/addonCategoryadd/$1";
$route['admin/catalog/addon-category/add'] = "admin/Catalog/addonCategoryadd";

$route['admin/size/view'] = "admin/Size/sizeView";
$route['admin/size/view/(:num)'] = "admin/Size/sizetView/$1";
$route['admin/size/add'] = "admin/Size/sizeAdd";
$route['admin/size/edit/(:any)'] = "admin/Size/sizeAdd/$1";

$route['admin/ingredient/view'] = "admin/Ingredient/ingredientView";
$route['admin/ingredient/view/(:num)'] = "admin/Ingredient/ingredientView/$1";
$route['admin/ingredient/add'] = "admin/Ingredient/ingredientAdd";
$route['admin/ingredient/edit/(:any)'] = "admin/Ingredient/ingredientAdd/$1";

$route['admin/tablebooking/view'] = "admin/Tablebooking/tableBookingView";
$route['admin/tablebooking/view/(:num)'] = "admin/Tablebooking/tableBookingView/$1";
$route['admin/tablebooking/edit/(:any)'] = "admin/Tablebooking/tableBookingAdd/$1";
$route['admin/tablebooking/add'] = "admin/Tablebooking/tableBookingAdd";
$route['admin/tablebooking/setting/view'] = "admin/Tablebooking/tableBookingSettingView";
$route['admin/tablebooking/setting/view/(:num)'] = "admin/Tablebooking/tableBookingSettingView/$1";
$route['admin/tablebooking/setting/edit/(:any)'] = "admin/Tablebooking/tableBookingSettingEdit/$1";
$route['admin/tablebooking/setting/edit'] = "admin/Tablebooking/tableBookingSettingEdit";

$route['admin/user/view'] = "admin/User/userView";
$route['admin/user/view/(:num)'] = "admin/User/userView/$1";

$route['admin/offer/view'] = "admin/Offer/offerView";
$route['admin/offer/view/(:num)'] = "admin/Offer/offerView/$1";
$route['admin/offer/add'] = "admin/Offer/offerAdd";
$route['admin/offer/edit/(:any)'] = "admin/Offer/offerAdd/$1";

$route['admin/voucher/view'] = "admin/Voucher/voucherView";
$route['admin/voucher/view/(:num)'] = "admin/Voucher/voucherView/$1";
$route['admin/voucher/add'] = "admin/Voucher/voucherAdd";
$route['admin/voucher/edit/(:any)'] = "admin/Voucher/voucherAdd/$1";

$route['admin/seo/view/(:num)'] = "admin/Seo/view/$1";
$route['admin/seo/edit/(:any)'] = "admin/Seo/add/$1";

$route['admin/points-settings'] = "admin/Points/setting";

$route['admin/addon-item/view/(:num)'] = "admin/Addonitem/addonItemView/$1";
$route['admin/addon-item/view'] = "admin/Addonitem/addonItemView";
$route['admin/addon-item/edit/(:any)'] = "admin/Addonitem/addonItemAdd/$1";
$route['admin/addon-item/add'] = "admin/Addonitem/addonItemAdd";

$route['admin/product/view/(:num)'] = "admin/Product/productView/$1";
$route['admin/product/view'] = "admin/Product/productView";
$route['admin/product/edit/(:any)'] = "admin/Product/productAdd/$1";
$route['admin/product/add'] = "admin/Product/productAdd";
$route['admin/product/upload-item'] = "admin/Product/uploadItem";

$route['admin/dinein-product/view/(:num)'] = "admin/Product/dineProductView/$1";
$route['admin/dinein-product/view'] = "admin/Product/dineProductView";
$route['admin/dinein-product/edit/(:any)'] = "admin/Product/dineProductAdd/$1";
$route['admin/dinein-product/add'] = "admin/Product/dineProductAdd";

$route['admin/delivery-charges/view'] = "admin/Deliverycharge/deliveryChargesView";
$route['admin/delivery-charges/view/(:num)'] = "admin/Deliverycharge/deliveryChargesView/$1";
$route['admin/delivery-charges/edit/(:any)'] = "admin/Deliverycharge/deliveryChargesAdd/$1";
$route['admin/delivery-charges/add'] = "admin/Deliverycharge/deliveryChargesAdd";

$route['admin/fixed-delivery-charges/view'] = "admin/Deliverycharge/fixedDeliveryChargesView";
$route['admin/fixed-delivery-charges/view/(:num)'] = "admin/Deliverycharge/fixedDeliveryChargesView/$1";
$route['admin/fixed-delivery-charges/edit/(:any)'] = "admin/Deliverycharge/fixedDeliveryChargesAdd/$1";
$route['admin/fixed-delivery-charges/add'] = "admin/Deliverycharge/fixedDeliveryChargesAdd";

$route['admin/order/view'] = "admin/Order/orderView";
$route['admin/order/view/(:num)'] = "admin/Order/orderView/$1";
$route['admin/order/new-order'] = "admin/Order/newOrderView";
$route['admin/order/new-order/(:num)'] = "admin/Order/newOrderView/$1";
$route['admin/order/today-order'] = "admin/Order/todayOrderView";
$route['admin/order/today-order/(:num)'] = "admin/Order/todayOrderView/$1";
$route['admin/order/today-sales'] = "admin/Order/todaySalesOrderView";
$route['admin/order/today-sales/(:num)'] = "admin/Order/todaySalesOrderView/$1";

$route['admin/reports/sales'] = "admin/Reports/salesReport";
$route['admin/reports/sales/(:num)'] = "admin/Reports/salesReport/$1";
$route['admin/reports/sales-summary'] = "admin/Reports/salesSummaryReport";
$route['admin/reports/sales-summary/(:num)'] = "admin/Reports/salesSummaryReport/$1";
$route['admin/reports/booking'] = "admin/Reports/bookingReport";
$route['admin/reports/booking/(:num)'] = "admin/Reports/bookingReport/$1";




//front
$route['about-us'] = "Pages/about";
$route['contact-us'] = "Pages/contact";
$route['terms'] = "Pages/terms";
$route['policy'] = "Pages/policy";
$route['privacy'] = "Pages/privacy";

$route['area/(:any)'] = "Restaurant/list/$1";
$route['area/(:any)/(:num)'] = "Restaurant/list/$1/$2";
$route['store/(:any)'] = "Restaurant/storeProducts/$1";

$route['user/change-password'] = "User/changePassword";
$route['user/address'] = "User/addressList";

$route['checkout'] = "Checkout/checkout";
$route['checkout/applypromocode'] = "Checkout/applypromocode";
$route['checkout/refreshCheckout'] = "Checkout/refreshCheckout";
$route['checkout/removepromocode'] = "Checkout/removepromocode";
$route['checkout/removePoint'] = "Checkout/removePoint";
$route['checkout/applyPoint'] = "Checkout/applyPoint";
$route['checkout/(:any)'] = "Checkout/checkout/$1";

$route['order/history'] = "Order/orderHistory";
$route['order/history/(:num)'] = "Order/orderHistory/$1";
$route['order/payment-review'] = "Order/paymentReview";
