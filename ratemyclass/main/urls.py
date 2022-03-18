from django.urls import path
from . import views
from register import views as reg

urlpatterns = [
    path("", reg.register, name="home"),
    path("login/", reg.login, name="login"),
    path("register/", reg.register, name="register")
]