from django.shortcuts import render
from django.http import HttpResponse, HttpResponseRedirect
# Create your views here.
def login(response):
    return render(response, "register/login.html", {})
    # return HttpResponse("<h1>This is the Login Page</h1>")

def register(response):
    return render(response, "register/register.html", {})