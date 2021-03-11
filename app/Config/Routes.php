<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
//$routes->setDefaultController('Customer');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

//สำหรับสมัครสามาชิก
$routes->post('/customers/addcustomer', 'Customer::addCustomer');
//สำหรับเพิ่มที่อยู่
$routes->post('/customers/addaddress', 'Customer::addAddress');
//สำหรับแสดงหน้าข้อมูลส่วนตัว
$routes->post('/customers/getprofile', 'Customer::getProfile');
//สำหรับอัพเดรทข้อมูลส่วนตัว
$routes->put('/customers/updateprofile/(:any)', 'Customer::updateProfile/$1');
//สำหรับอัพเดรทที่อยู่
$routes->post('/customers/updateaddress', 'Customer::updateAddress');

//ส่วนหมวดหมู่
//แสดงหมวดหมู่ทั้งหมด
$routes->get('/categorys/showcate', 'Category::showCate');
//สำหรับเพิ่มหมวดหมู่
$routes->post('/categorys/addcate', 'Category::addCate');
//สำหรับแก้ไขหมวดหมู่
$routes->put('/categorys/updatecate/(:any)', 'Category::updateCate/$1');
//สำหรับเปลี่ยนเป็น non-active
$routes->put('/categorys/updatestatuscate/(:any)', 'Category::updateStatus/$1');

//ส่วนของแบรนด์
//แสดงแบร์นทั้งหมด
$routes->get('/brands/showbrand', 'Brand::showBrand');
//สำหรับเพิ่มแบร์นสินค้า
$routes->post('/brands/addbrand', 'Brand::addBrand');
//สำหรับแก้ไขแบร์นสินค้า
$routes->put('/brands/updatebrand/(:any)', 'Brand::updateBrand/$1');

//ส่วนของสินค้า
//แสดงสินค้าและรายละเอียดต่างๆ
$routes->get('/products/showproduct', 'Product::showProduct');
//เพิ่มสินค้า
$routes->post('/products/addproduct', 'Product::addProduct');
//แก้ไขข้อมูลสินค้า
$routes->put('/products/updateproduct/(:any)', 'Product::updateProduct/$1');




/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
