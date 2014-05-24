<?php

class PluginManagerController extends BaseController {

	public function __construct()
	{
		parent::__construct();
		$this->theme->setActive('manage');
	}

	public function getIndex()
	{
		$this->theme->breadcrumb()->
			add('Home', URL::to('/'))->
			add('Manage Nestor', URL::to('/manage'))->
			add('Manage Plug-ins');
		return $this->theme->scope('plugin.index')->render();
	}

	public function getAdvanced()
	{
		$this->theme->breadcrumb()->
			add('Home', URL::to('/'))->
			add('Manage Nestor', URL::to('/manage'))->
			add('Manage Plug-ins');
		return $this->theme->scope('plugin.advanced')->render();
	}

	public function postUpload()
	{
		$uploadSuccess = FALSE;

		$file = Input::file('plugin_file');

		$rules = array('plugin_file'  => 'mimes:zip,gzip');
		$data  = array('plugin_file' => Input::file('plugin_file'));

		$validation = Validator::make($data, $rules);

		if ($validation->passes())
        {
            $filename  = /*str_random(6) . '_' . */$file->getClientOriginalName();
			Log::info(sprintf('Uploading new plugin %s', $filename));
			$destinationPath = base_path() . '/plugins/';
			$uploadSuccess = $file->move($destinationPath, $filename);

			if ($uploadSuccess)
			{
				Log::info('Plug-in uploaded!');
				$uploadedFile = $destinationPath . DIRECTORY_SEPARATOR . $filename;
				$zip = new ZipArchive;
				$res = $zip->open($uploadedFile);
				if ($res === TRUE) {
				  	$zip->extractTo($destinationPath);
				  	$zip->close();
				  	return Redirect::to('/pluginManager/installed')
				  		->with('success', sprintf('Plugin installed!', $filename));
				} else {
				  	Log::error(sprintf("Error unzipping %s", $uploadedFile));
				  	return Redirect::to('/pluginManager/installed')
				  		->with('warning', sprintf('Error unzipping plug-in file', $filename));
				}
			}
			else
			{
				Log::error('Failed to upload the plug-in');
			}
        } else {
        	Log::error('Invalid plug-in file');
        	$messages = new Illuminate\Support\MessageBag;
			$messages->add('nestor.customError', 'Please select a valid file');
			return Redirect::to('/pluginManager/advanced')
				->withInput()
				->withErrors($messages);
        }
	}

	public function postRebuildCache()
	{
		$pluginManager = Nestor::getPluginManager();
		try
		{
			$pluginManager->rebuildCache();
			return Redirect::to('/pluginManager/advanced')
		  		->with('success', sprintf('Cache rebuilt!'));
		}
		catch (Exception $e)
		{
			Log::error("Failed to rebuild cache: " . $e->getMessage());
			$messages = new Illuminate\Support\MessageBag;
			$messages->add('nestor.customError', 'Failed to rebuild cache: ' . $e->getMessage());
			return Redirect::to('/pluginManager/advanced')
				->withInput()
				->withErrors($messages);
		}
	}

}
