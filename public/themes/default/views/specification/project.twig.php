<div>
  <h4>Project {{ HTML.link('/projects/' ~ node.node_id, node.display_name, {'class': ''}) }}</h4>
  <h5>Create a test suite</h5>

  {{ Form.open({'route': 'testsuites.store', 'class': 'form-horizontal'}) }}
    {{ Form.hidden('project_id', current_project.id) }}
    {{ Form.hidden('ancestor', node.descendant) }}
    <div class="form-group">
      {{ Form.label('name', 'Name', {'class': 'control-label col-xs-2'}) }}
      <div class="col-xs-10">
        {{ Form.input('text', 'name', old.name, {'id':"name", 'class': "form-control", 'placeholder': 'Name'}) }}
      </div>
    </div>
    <div class="form-group">
      {{ Form.label('description', 'Description', {'class': 'control-label col-xs-2'}) }}
      <div class="col-xs-10">
        {{ Form.textarea('description', old.description, {'id': "description", 'rows': "3", 'class': "form-control", 'placeholder': 'Description'}) }}
      </div>
    </div>
    <div class="form-group">
      <div class='col-xs-8 col-xs-offset-2'>
        {{ Form.submit('Create', {'class': "btn btn-primary"}) }}
      </div>
    </div>
  {{ Form.close() }}
</div>