关于系统错误码，我们设想，按照如下思路来做，首先按照各个子系统分类，定义系统所有的错误码，

整个家庭保洁的错误码，按照功能的不同分类，共分为5类，

# iOS客户端Native,10xx

# Android客户端Native, 11xx

# MobileFront客户端, 2xxx

# API,3xxx

# Boss, 4xxx

# Core,5xxx.

  * 系统模块错误 50xx
  * 门店模块信息 51xx
  * 阿姨模块错误 52xx
  * 客户模块错误 53xx
  * 订单模块错误 54xx
  * 支付模块错误 55xx
  * 运营模块错误 56xx
  * 财务模块错误 57xx


# DbBase, 6xxx

  * 系统模块错误 60xx
  * 门店模块信息 61xx
  * 阿姨模块错误 62xx
  * 客户模块错误 63xx
  * 订单模块错误 64xx
  * 支付模块错误 65xx
  * 运营模块错误 66xx
  * 财务模块错误 67xx



每个错误码都必须是4为数字。例如客户端的所有错误码，第一位是1， 第二位是功能的分类， 第三，四位可以是更具体的数字。
这样的设计，便于开发工程师，测试工程师，运维工程师，客户服务人员， 用户，阿姨之间便于沟通。


# 客户端Native错误码

* 1101  手机无可用网络
* 1102  手机号格式输入不规范
* 1103  。。。。
# 客户端MobileFront错误码

* 21**
* 22**

# API错误码

# BOSS端错误码

# 阿姨端错误码
