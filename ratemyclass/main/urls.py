from django.urls import path
from . import views

urlpatterns = [
    path("", views.login, name="home"),
    path("login/", views.login, name="login"),
    path("register/", views.register, name="register"),
]