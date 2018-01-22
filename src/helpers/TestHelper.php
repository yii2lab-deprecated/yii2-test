<?php

namespace yii2lab\test\helpers;

use SebastianBergmann\CodeCoverage\Node\File;
use yii\helpers\ArrayHelper;
use yii2lab\app\domain\helpers\Config;
use yii2lab\app\domain\helpers\Env;
use yii2lab\helpers\yii\FileHelper;

class TestHelper {
	
	const DEFAULT_APPLICATION_PATH = 'vendor/yii2lab/yii2-app/tests/store/app';
	
	public static function loadTestConfig($config = [], $path = self::DEFAULT_APPLICATION_PATH) {
		Env::init($path);
		$definition = Env::get('config');
		$testConfig = Config::load($definition);
		return ArrayHelper::merge($testConfig, $config);
	}
	
	public static function replacePath($definition, $path) {
		$filters = [];
		foreach($definition['filters'] as $filter) {
			$filter = self::filterItem($filter, $path);
			if($filter) {
				$filters[] = $filter;
			}
		}
		$definition['filters'] = $filters;
		return $definition;
	}
	
	public static function makeConfigFromPath($path) {
		$path = FileHelper::normalizePath($path);
		$path = FileHelper::trimRootPath($path);
		$definition = Env::get('config');
		$definition = TestHelper::replacePath($definition, $path);
		$testConfig = Config::load($definition);
		return $testConfig;
	}
	
	private static function filterItem($filter, $path) {
		if(is_string($filter)) {
			return $filter;
		}
		if($filter['app'] == 'vendor/yii2lab/yii2-app/tests/store/app/console') {
			return null;
		}
		if($filter['app'] == 'vendor/yii2lab/yii2-app/tests/store/app/common') {
			$filter['app'] = $path;
		}
		return $filter;
	}
	
}