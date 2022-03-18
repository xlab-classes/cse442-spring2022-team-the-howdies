from django.db import models
from django.contrib.auth.models import User
# Create your models here.

class ToDoList(models.Model):
    #User parameter links to user
    #when saving a record t, do t.save(); response.user.todolist.add(t)
    user = models.ForeignKey(User, on_delete=models.CASCADE, related_name="todolist", null=True)
    name = models.CharField(max_length=200)

    def ___str__(self):
        return self.name