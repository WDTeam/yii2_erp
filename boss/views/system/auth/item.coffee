# 权限分组
a = ->

	"家政管理":[
		"shopmanager/shop-manager/index"
		"shopmanager/shop-manager/create"

	],"门店管理":[
		"shop/shop/index"
		"shop/shop/create"

	],"阿姨管理":[
		"worker/worker/index"
		"worker/worker/create"

	],"客户管理":[
		"customer/customer/index"
		"customer/customer-comment/index"
		"customer/customer-comment-tag/index"

	],"订单管理":[
		"order/order/index"
		"order/order/create"
		"order/order/assign"
		"order/auto-assign/index"
		"order/order-complaint/index"

	],"服务管理":[
		"operation/operation-category/index"
		"operation/operation-city/index"
		"operation/operation-city/opencity"
		"operation/operation-selected-service/index"

	],"CMS管理":[
		"operation/operation-platform/index"
		"operation/operation-advert-position/index"
		"operation/operation-advert-content/index"
		"operation/operation-advert-release/index"

	],"优惠券管理":[
		"operation/coupon/coupon/index"
		"operation/coupon/coupon/create"

	],"运营管理":[
		"operation/operation-boot-page/index"
		"operation/worker-task/index"

	],"服务卡管理":[
		"operation/operation-service-card-info/index"
		"operation/operation-service-card-sell-record/index"
		"operation/operation-service-card-with-customer/index"
		"operation/operation-service-card-consume-record/index"

	],"对账管理":[
		"finance/finance-pay-channel/index"
		"finance/finance-header/index"
		"finance/finance-pop-order/index"
		"finance/finance-record-log/index"
		"finance/finance-pop-order/billinfo"
		"finance/finance-pop-order/bad"

	],"结算管理":[
		"finance/finance-settle-apply/self-fulltime-worker-settle-index"
		"finance/finance-shop-settle-apply/index"
		"finance/finance-settle-apply/self-fulltime-worker-settle-index"
		"finance/finance-settle-apply/query"
		"finance/finance-shop-settle-apply/query"

	],"退款管理":[
		"finance/finance-refund/index"
		"finance/finance-refund/countinfo"

	],"赔偿管理":[
		"finance/finance-compensate/finance-confirm-index"
		"finance/finance-compensate/index"

	],"报表管理":[
		"finance/finance-office-count/indexoffice"
	]