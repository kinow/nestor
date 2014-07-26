{% block content %}
<script type='text/x-template' id='sql-textarea-template'>
    <label for='<%= data.name %>' class='col-xs-2 control-label'><%= data.description %></label>
    <div class="col-xs-10">
        <textarea class='form-control col-xs-12' rows='3' id='<%= data.name %>' name='<%= data.name %>'></textarea>
    </div>
</script>

<div class='row'>
    <div class='col-xs-12'>
        {{ Form.open({'route': 'reports.store', 'class': 'form-horizontal', 'role': 'form'}) }}
            <div class="form-group">
                {{ Form.label('report_type_id', 'Report type', {'class': 'col-xs-2 control-label'}) }}
                <div class="col-xs-10">
                    {{ Form.select('report_type_id', ['-- Choose one --'] + reportTypes.lists('name', 'id'), null, {'id':"report_type_id", 'class': "form-control"}) }}
                </div>
            </div>
            <div class="form-group">
                {{ Form.label('name', 'Name', {'class': 'col-xs-2 control-label'}) }}
                <div class="col-xs-10">
                    {{ Form.input('text', 'name', old.name, {'id':"name", 'class': "form-control"}) }}
                </div>
            </div>
            <div class="form-group">
                {{ Form.label('description', 'Description', {'class': 'col-xs-2 control-label'}) }}
                <div class="col-xs-10">
                    {{ Form.textarea('description', old.description, {'id': "description", 'rows': "3", 'class': "form-control col-xs-12"}) }}
                </div>
            </div>
            <div class='form-group yui3-skin-sam' id='sql_holder'>
            </div>
            <div class="form-group">
                <div class='col-xs-10 col-xs-offset-2'>
                    {{ Form.submit('Add', {'class': "btn btn-primary"}) }}&nbsp; {{ HTML.link(URL.previous(), 'Cancel', {'class': 'btn'}) }}
                </div>
            </div>
        {{ Form.close() }}
    </div>
</div>
<script type='text/javascript'>
templatecallback = function(Y) {
    var sqlHolder = Y.one('#sql_holder');
    Y.one('#report_type_id').on('change', function(e) {
        e.preventDefault();
        sqlHolder.get('childNodes').remove();
        var val = this.get('value');
        if (val == 1) {
            var sqlTextArea = Y.Template.Micro.compile(Y.one('#sql-textarea-template').getHTML());
            var renderedSqlTextArea = sqlTextArea({name: 'report_sql', description: 'SQL script'});
            sqlHolder.appendChild(renderedSqlTextArea);
        }
    });
}
</script>
{% endblock %}
