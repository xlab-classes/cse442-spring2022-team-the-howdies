from django.urls import path, include
from . import views
from register import views as reg
from register.views import ResetPasswordView

urlpatterns = [
    path('home/', views.home, name="home"),
    path('', include("django.contrib.auth.urls")),
    path("register/", reg.register, name="register"),
    #path("password-reset/", ResetPasswordView.as_view(), name="password_reset"),


    path('reset_password/',
     auth_views.PasswordResetView.as_view(template_name="main/password_reset.html"),
     name="reset_password"),

    path('reset_password_sent/', 
        auth_views.PasswordResetDoneView.as_view(template_name="main/password_reset_sent.html"), 
        name="password_reset_done"),

    path('reset/<uidb64>/<token>/',
     auth_views.PasswordResetConfirmView.as_view(template_name="main/password_reset_form.html"), 
     name="password_reset_confirm"),

    path('reset_password_complete/', 
        auth_views.PasswordResetCompleteView.as_view(template_name="main/password_reset_done.html"), 
        name="password_reset_complete"),
]

'''
1 - Submit email form                         //PasswordResetView.as_view()
2 - Email sent success message                //PasswordResetDoneView.as_view()
3 - Link to password Rest form in email       //PasswordResetConfirmView.as_view()
4 - Password successfully changed message     //PasswordResetCompleteView.as_view()
'''