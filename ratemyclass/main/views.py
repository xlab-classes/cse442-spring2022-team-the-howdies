from django.shortcuts import render, redirect
# Create your views here.


def home(response):
    if not response.user.is_authenticated:
        return redirect('login')
    return render(response, "main/base.html", {})
