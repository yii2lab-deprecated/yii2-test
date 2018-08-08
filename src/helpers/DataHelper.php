<?php

namespace yii2lab\test\helpers;

use yii\helpers\ArrayHelper;
use yii2lab\domain\base\BaseDto;
use yii2lab\domain\BaseEntity;
use yii2lab\helpers\yii\FileHelper;
use yii2lab\store\Store;

class DataHelper {
	
	public static function loadForTest($package, $method, $defaultData = null) {
		//$method = basename($method);
		$method = str_replace('tests\\', '', $method);
		$path = str_replace('::', SL, $method);
		$fileName = '_expect' . SL . $path . '.json';
		return DataHelper::load($package, $fileName, $defaultData);
	}
	
	public static function load($packageName, $filename, $defaultData = null) {
		$driver = FileHelper::fileExt($filename);
		$store = new Store($driver);
		$configExpect = $store->load(self::getDataFilename($packageName, $filename));
		if(empty($configExpect)) {
			if(is_array($defaultData) || $defaultData instanceof BaseEntity || $defaultData instanceof BaseDto) {
				$defaultData = ArrayHelper::toArray($defaultData);
			}
			self::save($packageName, $filename, $defaultData);
			return $defaultData;
		}
		return $configExpect;
	}
	
	public static function save($packageName, $filename, $data) {
		$driver = FileHelper::fileExt($filename);
		$store = new Store($driver);
		$store->save(self::getDataFilename($packageName, $filename), $data);
	}
	
	private static function getDataFilename($packageName, $filename) {
		return VENDOR_DIR . DS . $packageName . DS . 'tests' . DS . $filename;
	}
	
}
