-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`t_user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`t_user` (
  `id` INT NULL AUTO_INCREMENT,
  `user_tel` VARCHAR(20) NOT NULL COMMENT '用户电话',
  `reg_time` DATETIME NOT NULL COMMENT '注册时间',
  `reg_ip` INT NOT NULL COMMENT '注册的IP',
  `status` TINYINT NOT NULL DEFAULT 0 COMMENT '帐号状态.0: 普通.1: 已认证',
  `last_login_time` DATETIME NOT NULL COMMENT '上次登录时间',
  `user_name` VARCHAR(45) NULL COMMENT '用户名称',
  `user_identy` VARCHAR(45) NULL COMMENT '身份证号',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `user_tel_UNIQUE` (`user_tel` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`t_product`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`t_product` (
  `id` INT NULL AUTO_INCREMENT,
  `pro_name` VARCHAR(100) NOT NULL,
  `create_time` DATETIME NULL,
  `cat_id` INT NOT NULL,
  `info` TEXT NULL COMMENT '商品详细信息.json格式.如{‘1’:’235’,’8’:’2’}key里，1，8 表示 t_category_module的记录。value的值根据 module 的类型决定。如果是枚举值，则对应 t_category_const 里的值。如果是 text 类，则直接为用户输入的值',
  `status` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '状态 。0:未上架;1:已上架',
  `price` DECIMAL(10,2) NULL COMMENT '价格',
  `remain_cnt` INT NOT NULL DEFAULT 0 COMMENT '库存数',
  `saled_cnt` INT NOT NULL DEFAULT 0 COMMENT '卖出数',
  `pro_img` TEXT NULL,
  `content` TEXT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`t_order`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`t_order` (
  `id` INT NULL AUTO_INCREMENT,
  `pro_info` TEXT NOT NULL COMMENT '订单详情.一个订单可以有多个商品.这里用 json 格式.如：{‘1’:{‘cnt’:2,’price’:29.5},’3’:{‘cnt’:1,’price’:25}}',
  `user_id` INT NOT NULL,
  `create_time` DATETIME NOT NULL,
  `status` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '0：未处理；1：已付款，未发货；2：已发货；3：已发货；4：已收货，订单完成',
  `finish_time` DATETIME NULL,
  `real_address_id` INT NULL,
  `pay_type` TINYINT(1) NULL COMMENT '支付方式',
  `pay_money` DECIMAL(10,2) NOT NULL COMMENT '支付金额',
  `pay_time` DATETIME NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`t_address`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`t_address` (
  `id` INT NULL AUTO_INCREMENT COMMENT '用户收货地址表',
  `user_id` INT NOT NULL COMMENT '用户ID',
  `ad_province` TINYINT NULL COMMENT '省',
  `ad_city` SMALLINT NULL COMMENT '市',
  `ad_detail` VARCHAR(500) NULL COMMENT '详细地址',
  `is_default` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '是否是默认地址. 0:不是；1：是',
  `rec_name` VARCHAR(45) NULL COMMENT '收件人名称',
  `rec_phone` VARCHAR(45) NULL COMMENT '收件人电话',
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`table2`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`table2` (
)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`t_admin`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`t_admin` (
  `id` INT NULL AUTO_INCREMENT,
  `admin_name` VARCHAR(45) NOT NULL,
  `admin_pwd` VARCHAR(45) NOT NULL,
  `role_id` INT NOT NULL DEFAULT 1,
  `create_time` DATETIME NOT NULL,
  `admin_email` VARCHAR(200) NOT NULL,
  `status` TINYINT(1) NOT NULL DEFAULT 0,
  `nick_name` VARCHAR(45) NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `admin_email_UNIQUE` (`admin_email` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`t_ favorite`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`t_ favorite` (
  `id` INT NULL AUTO_INCREMENT,
  `uesr_id` INT NOT NULL COMMENT '用户ID',
  `pro_id` INT NOT NULL COMMENT '商品ID',
  `add_time` DATETIME NOT NULL COMMENT '关注的时间',
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`t_log`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`t_log` (
  `id` INT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `action_type` INT NOT NULL COMMENT '事件类型',
  `act_time` DATETIME NOT NULL COMMENT '事件发生的时间',
  `is_admin` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '是否是管理端的操作.0 表示不是. 1 表示是',
  `action_detail` VARCHAR(200) NULL,
  `target_id` INT NULL COMMENT '操作对应的目标ID。如果是买商品，则对应的是商品ID。如果是审核用户信息则对应用户ID',
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`t_history`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`t_history` (
  `id` INT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `pro_id` INT NULL,
  `view_time` DATETIME NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`t_category`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`t_category` (
  `id` INT NULL AUTO_INCREMENT,
  `cat_name` VARCHAR(45) NOT NULL,
  `cat_level` TINYINT NOT NULL,
  `cat_parent` INT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '是否激活.1:是;0:否',
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`t_role`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`t_role` (
  `id` INT NULL AUTO_INCREMENT,
  `role_name` VARCHAR(45) NOT NULL,
  `role_right` INT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`t_category_config`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`t_category_config` (
)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`t_category_const`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`t_category_const` (
  `id` INT NULL AUTO_INCREMENT COMMENT '分类中用到的常量表',
  `mod_id` INT NULL COMMENT '常量所属的模块的ID',
  `const_text` VARCHAR(45) NULL COMMENT '常量文字描述.如颜色模块中的：白，黑',
  `const_val` INT NULL COMMENT '常量对应的值。如颜色中白对应的值是 1，黑对应的是 2',
  `show_order` INT NULL COMMENT '显示顺序.越大显示越靠前',
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`t_action`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`t_action` (
  `id` INT NULL AUTO_INCREMENT,
  `action_name` VARCHAR(45) NOT NULL COMMENT '操作名称.如：找回密码, 设置权限，审核用户认证信息',
  `is_admin` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '是否是管理端的操作.0 表示不是. 1 表示是',
  `action_detail` VARCHAR(200) NULL,
  `target_id` INT NULL COMMENT '操作对应的目标ID。如果是买商品，则对应的是商品ID。如果是审核用户信息则对应用户ID',
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`t_geo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`t_geo` (
  `id` INT NULL AUTO_INCREMENT,
  `geo_name` VARCHAR(45) NOT NULL COMMENT '地区地址',
  `geo_level` TINYINT NOT NULL COMMENT '地区层级.1: 省，直辖市；2：市；3：县；',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '地区是否激活.1:是;0:否',
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`t_category_module`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`t_category_module` (
  `id` INT NULL AUTO_INCREMENT COMMENT '模块列表。不同分类可能共用模块。',
  `mod_name` VARCHAR(45) NOT NULL COMMENT '模块名称.如:颜色，形状',
  `mod_type` TINYINT NOT NULL COMMENT '模块的类型.如：下拉框，单选框，复选框，输入框',
  `mod_dw` VARCHAR(45) NULL COMMENT '模块单位。如：斤（当type表示重量时），升（当type表示容量时）',
  `default_value` VARCHAR(45) NULL COMMENT '默认值',
  `mod_en_name` VARCHAR(45) NULL COMMENT '模块英文名称，用来显示在 form 里',
  `is_number` TINYINT(1) NULL COMMENT '是否是数字',
  `min_length` TINYINT NULL COMMENT '最短长度',
  `max_length` TINYINT NULL COMMENT '最长长度',
  `is_phone` TINYINT(1) NULL COMMENT '是否是电话',
  `is_email` TINYINT(1) NULL COMMENT '是否是邮件',
  `is_date` TINYINT(1) NULL COMMENT '是否是日期',
  `cat_id` INT NOT NULL,
  `is_active` VARCHAR(45) NOT NULL DEFAULT '1',
  `show_order` INT NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`t_module_rule`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`t_module_rule` (
  `id` INT NULL AUTO_INCREMENT COMMENT '模块数据的规则。如：必须是数字，必须是邮箱，长度必须是 6-12 位。',
  `is_number` TINYINT(1) NULL COMMENT '是否是数字',
  `min_length` TINYINT NULL COMMENT '最短长度',
  `max_length` TINYINT NULL COMMENT '最长长度',
  `is_phone` TINYINT(1) NULL COMMENT '是否是电话',
  `is_email` TINYINT(1) NULL COMMENT '是否是邮件',
  `is_date` TINYINT(1) NULL COMMENT '是否是日期',
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`t_ordre_address`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`t_ordre_address` (
  `id` INT NULL AUTO_INCREMENT COMMENT '用户收货地址表',
  `ad_province` TINYINT NULL COMMENT '省',
  `ad_city` SMALLINT NULL COMMENT '市',
  `ad_detail` VARCHAR(500) NULL COMMENT '详细地址',
  `rec_name` VARCHAR(45) NULL COMMENT '收件人名称',
  `rec_phone` VARCHAR(45) NULL COMMENT '收件人电话',
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`t_news`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`t_news` (
  `id` INT NULL AUTO_INCREMENT,
  `title` VARCHAR(200) NOT NULL,
  `head_img` VARCHAR(200) NULL,
  `content` TEXT NULL,
  `create_time` DATETIME NOT NULL,
  `status` TINYINT(1) NOT NULL DEFAULT 0,
  `is_galarry` TINYINT(1) NOT NULL DEFAULT 0,
  `is_link` TINYINT(1) NOT NULL DEFAULT 0,
  `link_url` VARCHAR(200) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`t_news_cat`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`t_news_cat` (
  `id` INT NULL AUTO_INCREMENT,
  `cat_name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
