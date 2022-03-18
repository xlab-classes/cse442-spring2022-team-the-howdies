from django.shortcuts import render, redirect
from django.http import HttpResponse, HttpResponseRedirect
from .forms import CreateNewList
from .forms import RegisterForm

# Create your views here.
def login(response):
    return render(response, "register/login.html", {})
    # return HttpResponse("<h1>This is the Login Page</h1>")

def register(response):
    if response.method == "POST":
        form = RegisterForm(response.POST)
        if form.is_valid():
            form.save()

        return redirect("/home")
    else:
        form = RegisterForm()
    return render(response, "register/register.html", {"form":form})
