# Ultra\Html Utilities
Utilities for creating HTML and Forms.

## HTML Helper
It provides the several utility functions

 * link: Generates a link
 * ul: Generates an unordered list
 * ol: Generates an ordered list
 * css: Generates a link tag referencing a given css file
 * script: Generates a script tag referencing a given javascript file

## Form Helper
It provides several utility functions to manage forms creation

 * open
 * close
 * label
 * input
 * password
 * select
 * button
 * submit
 * is_safe

## Using with Katar

```
<h1>Register New Account</h1>
{{ $form->open() }}
  <div class="input">
    {{ $form->label('Username', 'user') }}
    {{ $form->input('user') }}
  </div>
{{ $form->close() }}
```

