from django.conf.urls import url
from . import views

app_name = 'articles'
urlpatterns = [
    url(r'^(\d+)/$', views.detail, name = 'detail'),
    url(r'^(\d+)/add_comment/$', views.add_comment, name = 'add_comment'),
	url('', views.index, name = 'index'),
]
