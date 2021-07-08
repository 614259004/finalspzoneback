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
//สำหรับlogin
$routes->post('/customers/login','Customer::login');
//สำหรับดูข้อมูลที่อยู่
$routes->post('/customers/getaddress', 'Customer::getAddress');


//ส่วนหมวดหมู่
//แสดงหมวดหมู่ทั้งหมด
$routes->get('/categorys/showcate', 'Category::showCate');
//แสดงสินค้าสำหรับหมวดหมู่
$routes->post('/categorys/showproductbycate', 'Category::showProductbycate');
//สำหรับเพิ่มหมวดหมู่
$routes->post('/categorys/addcate', 'Category::addCate');
//สำหรับแก้ไขหมวดหมู่
$routes->put('/categorys/updatecate/(:any)', 'Category::updateCate/$1');
//สำหรับเปลี่ยนเป็น non-active
$routes->put('/categorys/updatestatuscate/(:any)', 'Category::updateStatus/$1');
//สำหรับลบหมวดหมู่
$routes->delete('/categorys/deletecate/(:any)', 'Category::deleteCate/$1');

//ส่วนของแบรนด์
//แสดงแบร์นทั้งหมด
$routes->get('/brands/showbrand', 'Brand::showBrand');
//แสดงสินค้าสำหรับแต่ละแบรนด์
$routes->post('/brands/showproductbybrand', 'Brand::showProductbybrand');
//สำหรับเพิ่มแบร์นสินค้า
$routes->post('/brands/addbrand', 'Brand::addBrand');
//สำหรับแก้ไขแบร์นสินค้า
$routes->put('/brands/updatebrand/(:any)', 'Brand::updateBrand/$1');
//สำหรับเปลี่ยนstatus brand
$routes->put('/brands/updatestatusbrand/(:any)', 'Brand::updateStatus/$1');
//สำหรับลบแบรนด์ที่ไม่ถูกเรียกใช้
$routes->delete('/brands/deletebrand/(:any)', 'Brand::deleteBrand/$1');

//ส่วนของสินค้า
//แสดงข้อมูลสินค้าทั้งหมด
$routes->get('/products/showproduct', 'Product::showProduct'); 
//แสดงสินค้าและรายละเอียดต่างๆ
$routes->post('/products/showproductbyid', 'Product::showProductbyid'); 
//แสดงไซส์ของสินค้าตัวนั้นๆ
$routes->get('/products/showproductandsize/(:any)', 'Product::showProductandSize/$1'); 
//แสดงไซส์ทั้งหมด
$routes->get('/products/showsize', 'Product::showSize'); 
//เพิ่มสินค้า
$routes->post('/products/addproduct', 'Product::addProduct'); 
//เพิ่มไซส์สินค้าและจำนวน
$routes->post('/products/addsize', 'Product::addSize'); 
//แก้ไขข้อมูลสินค้า
$routes->put('/products/updateproduct/(:any)', 'Product::updateProduct/$1'); 
//แก้ไขไซส์
$routes->put('/products/updatesize/(:any)', 'Product::updateSize/$1'); 
//ลบไซส์
$routes->post('/products/deletesize', 'Product::deleteSize');

//ส่วนเช็คซ้ำไม่ซ้ำ
//เช็คข้อมูลซ้ำของตารางหมวดหมู่สินค้า
$routes->post('/recheck/checkduplicatename', 'Recheck::checkDuplicateName');
$routes->post('/recheck/checkSize', 'Recheck::checkSize');




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
