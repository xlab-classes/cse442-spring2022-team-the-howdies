from django.urls import path, include
from . import views
from register import views as reg
from register.views import ResetPasswordView

urlpatterns = [
    path('', views.home, name="home"),
    path('', include("django.contrib.auth.urls")),
    path("register/", reg.register, name="register"),
    path("password-reset/", ResetPasswordView.as_view(), name="password_reset")
]