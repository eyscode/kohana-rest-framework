# Kohana REST framework (KRF)

**A module for building RESTApis, that works well with Kohana ORM.**
**Author:** Eysenck Gómez.

## Overview

Kohana REST framework is a module thats makes pretty easy to build RESTApis in Kohana that works well with the Kohana ORM, the design of the architecture is inspired on DRF (Django REST Framework), and uses the good OOP funcionality of PHP, like as Clases, Traits, to reuse code and stay DRY. The primary goal of KRF is to create a module that allow to write on easy way a REST controllers to serve WebApis on a project, that integrates on a perfect way with ORM, and encode json of the relationships between models (nested, related, etc) . Even though there are other modules for write RESTApis in Kohana, doing so requires a lot of work for integrate with the Kohana ORM on medium/big projects. In a near future, hope that KRF will be support Oauth and Ouath2 integration for auth in the RESTApi, and add more funcionality from the DRF (Token Authentication will be avalaible soon) and good practices of others REST modules.

## Features

KRF provides a number of features, such as:

* RESTApi Controller and RESTApi Model controller that works with ORM Model.
* RESTApi Controller to List, Create, Update, Delete and mix of them extended of ORM Model, so allow to write a complete RESTApi of a model very easy and fast, also these Controllers are configurable.
* Serializer to encode the data of a ORM Model and works well with relationships between models, supports field related, primary key related and nested related representations of relationships. In a future Hyperlinked related will be supported.
* Mixins whose implements list, create, destroy, retrieve an update actions for ORM models, this mixins can be included in a Controller Rest thanks to the Traits implementation.
* Friendly errors and manage of the status and details of the errors.
* All of the HTTP Status with name and number to give better visualition of response status code.
* Token Authorization integrated with Kohana Auth (soon).

## Requirements

* PHP >= 5.3
* Kohana >= 3.2

## Configuration

Add the krf module to your modules dir.

Add 'krf' to modules array in your boostrap.php

	Kohana::modules(array(
		...
		'krf' => MODPATH . 'krf',
	));

Copy the config file krf.php to your config dir. The content will be like this:

	<?php defined('SYSPATH') OR die('No direct access allowed.');

	return array
	(
		'paginate_by' => 2 // null if not want to paginate the results
	);
	
## Getting Started

Create a Serializer for work with a ORM Model, for example Post model.

	class PostSerializer extends ModelSerializer
	{
		protected $_orm_model = 'Post';
		protected $_fields = array( // here the fields that want to make visible in the api
			'id',
			'title',
			'date_creation'
		);
		protected $_has_many = array(
			'comments' => array(Relationship::PrimaryKeyRelated)
			// Anothers relationships types
			// 'comments' => array(Relationship::NestedRelated, 'CommentSerializer')
			// 'comments' => array(Relationship::FieldRelated, 'title')
		);
		protected $_belongs_to = array(
			'autor' => array(Relationship::PrimaryKeyRelated)
			// Anothers relationships types
			//'autor' => array(Relationship::NestedRelated, 'UserSerializer')
			//'autor' => array(Relationship::FieldRelated, 'name')
		);
	}

Create a RESTApi Controller to list and create records of my Post model.

	class Controller_PostListCreate extends Controller_RESTApi_Model_ListCreate
	{
		protected $_serializer = 'PostSerializer';
	}

Next, set the url on your boostrap.php

	Route::set('posts-list-create', 'posts')
		->defaults(array(
			'controller' => 'postlistcreate',
			'action' => 'index' //always the action will be index
	));

And then, suppose that we have the post and comment models, and the comments serializer if we use 'nested related relationship', already have the RESTAPi for list and create Post records and only allow GET and POST methods.

## License

### Apache v2.0

Copyright © 2013 Eysenck Gómez.

Unless otherwise noted, KRF is licensed under the Apache License, Version 2.0 (the "License"); you may not use these files except in
compliance with the License. You may obtain a copy of the License at:

[http://www.apache.org/licenses/LICENSE-2.0](http://www.apache.org/licenses/LICENSE-2.0)

Unless required by applicable law or agreed to in writing, software distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the License for the specific language governing permissions
and limitations under the License.
