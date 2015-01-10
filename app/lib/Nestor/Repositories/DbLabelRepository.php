<?php namespace Nestor\Repositories;

use Label;

class DbLabelRepository implements LabelRepository {

	public function all($projectId)
	{
		return Label::where('project_id', '=', $projectId);
	}

	public function find($id)
	{
		return Label::where('id', '=', $id);
	}

	public function create($projectId, $name, $color)
	{
		return Label::create(array('project_id' => $projectId, 'name' => $name, 'color' => $color));
	}

	public function update($id, $name, $color)
	{
		$label = $this->find($id);
		$label->name = $name;
		$label->color = $color;
		$label->save();
		return $label;
	}

	public function delete($id)
	{
		return Label::where('id', '=', $id)->delete();
	}

}