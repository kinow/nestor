{% block content %}

<div class="row">
    <div class="col-sm-6 col-md-4 col-md-offset-4">
        <h1 class="text-center login-title">Sign in to continue</h1>
        <div class="account-wall">
            <img class="profile-img" src="{{ URL.to('/themes/default/assets/img/photo.png') }}" alt="">
            {{ Form.open({'url': '/users/login', 'class': 'form-signin', 'role': 'form'}) }}
            <input type="text" class="form-control" placeholder="Email" name="email" required autofocus>
            <input type="password" class="form-control" placeholder="Password" name="password" required>
            <button class="btn btn-lg btn-primary btn-block" type="submit">
                Sign in</button>
            <label class="checkbox pull-left">
                <input type="checkbox" value="remember-me" name='remember'>
                Remember me
            </label>
            <a href="#" class="pull-right need-help">Need help? </a><span class="clearfix"></span>
            {{ Form.close() }}
        </div>
        <a href="{{ URL.to('/users/create') }}" class="text-center new-account">Create an account </a>
    </div>
</div>
{% endblock %}