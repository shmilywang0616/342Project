<?php
if (!isset($_GET['do']))
	return json_encode(array('success' => false, 'errorMessage' => 'invail request'));

$action = $_GET['do'];

switch ($action) {
	case 'get-category-list':
		exit(json_encode(getCategoryList()));
		break;
	case 'get-shop-list':
		exit(json_encode(getShopList()));
		break;
	case 'get-shop-detail':
		exit(json_encode(getShopDetail()));
		break;
	case 'get-coupon-list':
		exit(json_encode(getCouponList()));
		break;
	case 'get-coupon-list-by-category':
		exit(json_encode(getCouponListByCategory()));
		break;
	case 'get-coupon-list-by-shop':
		exit(json_encode(getCouponListByShop()));
		break;
	case 'fetch-coupon':
		exit(json_encode(getCouponDetail()));
		break;
	case 'get-coupon-list-by-categories':
		exit(json_encode(getCouponListByCategories()));
		break;
	default:
		return json_encode(array('success' => false, 'errorMessage' => 'invail request'));
		break;
}

function getCategoryList() {
	try {
		$conn = connectToDatabase();
		$stmt = $conn->prepare('SELECT id, name FROM category');
		$categories = [];
		if ($stmt->execute()) {
			while ($result = $stmt->fetch())
				$categories[] = array('id' => $result['id'], 'name' => $result['name']);
			return array('success' => true, 'result' => $categories);
		} else return array('success' => false, 'errorMessage' => 'database error');
	} catch (PDOException $ex) {
		return array('success' => false, 'errorMessage' => 'database error');
	}
}

function getShopList() {
	try {
		$conn = connectToDatabase();
		$stmt = $conn->prepare('SELECT id, name FROM shop');
		$shops = [];
		if ($stmt->execute()) {
			while ($result = $stmt->fetch())
				$shops[] = array('id' => $result['id'], 'name' => $result['name']);
			return array('success' => true, 'result' => $shops);
		} else return array('success' => false, 'errorMessage' => 'database error');
	} catch (PDOException $e) {
		return array('success' => false, 'errorMessage' => 'database error');
	}
}

function getShopDetail() {
	if (!isset($_GET['id']))
		return array('success' => false, 'errorMessage' => 'invaild request');
	if (empty($_GET['id'])) 
		return array('success' => false, 'errorMessage' => 'invaild request');
	$id = $_GET['id'];
	try {
		$conn = connectToDatabase();
		$stmt = $conn->prepare('SELECT * FROM shop WHERE id = :id');
		if ($stmt->execute(array('id' => $id))) {
			if ($result = $stmt->fetch())
				return array('id' => $result['id'], 'name' => $result['name'], 'address' => $result['address'], 'phone' => $result['phone'], 'email' => $result['email'], 'latitude' => $result['latitude'], 'longitude' => $result['longitude']);
			else
				return array('success' => false, 'errorMessage' => 'no record found');
		} else return array('success' => false, 'errorMessage' => 'database error');
	} catch (PDOException $e) {
		return array('success' => false, 'errorMessage' => 'database error');
	}
}

function getCouponList() {
	try {
		$conn = connectToDatabase();
		$stmt = $conn->prepare('SELECT id, description FROM coupon');
		$coupons = [];
		if ($stmt->execute()) {
			while ($result = $stmt->fetch())
				$coupons[] = array('id' => $result['id'], 'description' => $result['description']);
			return array('success' => true, 'result' => $coupons);
		} else return array('success' => false, 'errorMessage' => 'database error');
	} catch (PDOException $e) {
		return array('success' => false, 'errorMessage' => 'database error');
	}
}

function getCouponListByCategory() {
	if (!isset($_GET['categoryID']))
		return array('success' => false, 'errorMessage' => 'invaild request');
	if (empty($_GET['categoryID']))
		return array('success' => false, 'errorMessage' => 'invaild request');
	$categoryID = $_GET['categoryID'];
	$coupons = [];
	try {
		$conn = connectToDatabase();
		$stmt = $conn->prepare('SELECT id, description FROM coupon WHERE category_id = :category_id');
		if ($stmt->execute(array('category_id' => $categoryID))) {
			while ($result = $stmt->fetch())
				$coupons[] = array('id' => $result['id'], 'description' => $result['description']);
			return array('success' => true, 'result' => $coupons);
		} else return array('success' => false, 'errorMessage' => 'database error');
	} catch (PDOException $e) {
		return array('success' => false, 'errorMessage' => 'database error');
	}
}

function getCouponListByShop() {
	if (!isset($_GET['shopID']))
		return array('success' => false, 'errorMessage' => 'invaild request');
	if (empty($_GET['shopID']))
		return array('success' => false, 'errorMessage' => 'invaild request');
	$shopID = $_GET['shopID'];
	$coupons = [];
	try {
		$conn = connectToDatabase();
		$stmt = $conn->prepare('SELECT id, description FROM coupon WHERE shop_id = :shop_id');
		if ($stmt->execute(array('shop_id' => $shopID))) {
			while ($result = $stmt->fetch())
				$coupons = array('id' => $result['id'], 'description' => $result['description']);
			return array('success' => true, 'result' => $coupons);
		} else return array('success' => false, 'errorMessage' => 'database error');
	} catch (PDOException $e) {
		return array('success' => false, 'errorMessage' => 'database error');
	}
}

function getCouponDetail() {
	if (!isset($_GET['id']))
		return array('success' => false, 'errorMessage' => 'invaild request');
	if (empty($_GET['id']))
		return array('success' => false, 'errorMessage' => 'invaild request');
	$id = $_GET['id'];
	try {
		$conn = connectToDatabase();
		$stmt = $conn->prepare('SELECT * FROM coupon WHERE id = :id');
		if ($stmt->execute(array('id' => $id))) {
			if ($result = $stmt->fetch())
				return array('id' => $result['id'], 'shopID' => $result['shop_id'], 'categoryID' => $result['category_id'], 'imageURL' => $result['image_url'], 'description' => $result['description'], 'fromDate' => $result['from_date'], 'toDate' => $result['to_date'], 'code' => $result['code'], 'finePrint' => $result['fine_print']);
			else
				return array('success' => false, 'errorMessage' => 'no record found');
		} else return array('success' => false, 'errorMessage' => 'database error');
	} catch (PDOException $e) {
		return array('success' => false, 'errorMessage' => 'database error');
	}
}

function getCouponListByCategories() {
	if (!isset($_GET['categoryIDs']) || !isset($_GET['couponIDs']))
		return array('success' => false, 'errorMessage' => 'invaild request');
	if (empty($_GET['categoryIDs']) || empty($_GET['couponIDs']))
		return array('success' => false, 'errorMessage' => 'invaild request');
	$categoryIDs = $_GET['categoryIDs'];
	$couponIDs = $_GET['couponIDs'];
	$coupons = [];
	try {
		$conn = connectToDatabase();
		foreach ($categoryIDs as $categoryID) {
			$stmt = $conn->prepare('SELECT * FROM coupon WHERE category_id = :category_id');
			if ($stmt->excute(array('category_id' => $categoryID))) {
				while ($result = $stmt->fetch()) {
					if (!in_array($result['id'], $couponIDs)) {
						$coupons[] = array('id' => $result['id'], 'shopID' => $result['shop_id'], 'categoryID' => $result['category_id'], 'imageURL' => $result['image_url'], 'description' => $result['description'], 'fromDate' => $result['from_date'], 'toDate' => $result['to_date'], 'code' => $result['code'], 'finePrint' => $result['fine_print']);
					}
				}
			}
		}
		return array('success' => true, 'result' => $coupons);
	} catch (PDOException $e) {
		return array('success' => false, 'errorMessage' => 'database error');
	}
}

function connectToDatabase() {
	$conn = new PDO('mysql:host=localhost;dbname=cc', 'root', '');
	return $conn;
}
?>
