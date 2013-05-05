Larchy
======

Enables your Laravel 3 app to make use of the 'Stem and Leaf' view layouting pattern.


Requirements
------------

+ Laravel 3


Installation
------------

+ Just place the `Larchy.php` file and `Larchy` folder inside `application/libraries` directory.


Examples
--------

<pre>
class Example_Controller extends Base_Controller
{
  public function action_index()
  {
    $data = array(
      'title' => 'Example Page',
      'meta' => array(
        'description' => 'This is a meta description for SEO purposes.'
      )
    );
    $headers = array(
      'field-name' => 'value'
    );
    return Larchy::make($data, 200, $headers);
  }
}
</pre>

You could also specify a title string directly if you will not going to specify some other variables.

<pre>
class Example_Controller extends Base_Controller
{
  public function action_index()
  {
    return Larchy::make('Example Page');
  }
}
</pre>