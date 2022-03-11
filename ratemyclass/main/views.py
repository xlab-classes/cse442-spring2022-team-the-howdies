from django.shortcuts import render
from django.http import HttpResponse, HttpResponseRedirect
# Create your views here.

def login(response):
    return HttpResponse("<h1>This is the Login Page</h1>")

def register(response):
    return HttpResponse("<h1>This is the Register Page</h1>")