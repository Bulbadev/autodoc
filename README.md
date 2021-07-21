<!-- PROJECT SHIELDS -->
[![Contributors][contributors-shield]][contributors-url]
[![Forks][forks-shield]][forks-url]
[![Stargazers][stars-shield]][stars-url]
[![Issues][issues-shield]][issues-url]
[![LinkedIn][linkedin-shield]][linkedin-url]


<!-- PROJECT HEADER -->
<br />

<h3 align="center">API documentation generator for Laravel</h3>
<p align="center">
<br />
<a href="https://github.com/Bulbadev/autodoc/issues">Report Bug</a>
·
<a href="https://github.com/Bulbadev/autodoc/pulls">Request Feature</a>
</p>


<!-- TABLE OF CONTENTS -->
<details open="open">
  <summary>Table of contents</summary>
  <ol>
    <li>
      <a href="#about-the-project">About The Project</a>
    </li>
    <li>
      <a href="#getting-started">Getting Started</a>
      <ul>
        <li><a href="#installation">Installation</a></li>
      </ul>
    </li>
    <li><a href="#usage">Usage</a></li>
    <li><a href="#license">License</a></li>
  </ol>
</details>



<!-- ABOUT THE PROJECT -->
## About The Project

The idea of the project is to control documentation based on tests. 
Incomplete documentation will indicate incomplete test coverage.
Failed tests will prevent updating the documentation and making mistakes.
After updating the documentation in version control, you will be able to
check the changes. This package will keep your API up to date.

At the moment, the library supports the generation of documentation for
swagger according to the standard [OAS-3.0.3]. Further expansion is planned.

<!-- GETTING STARTED -->
## Getting Started
### Installation

1. Install the package using composer
   ```shell
   php composer require bulbadev/autodoc
   ```
2. Publish config and templates if you want to edit them
   ```shell
   php artisan vendor:publish
   ```

<!-- USAGE EXAMPLES -->
## Usage
1. Add middleware `Bulbadev\Autodoc\Middlewares\AutodocMiddleware` to your API Route group
   
2. Add trait `Bulbadev\Autodoc\TestCases\AutodocTestCaseTrait` to the group
   of tests that will be run to generate tests
   
3. Documentation generation relies on classes FormRequests, Request
   и Response of framework Laravel. Your task is to always create a class
   that inherits FormRequest and specify annotations in it. An example can
   be found in `Bulbadev\Autodoc\FormRequest\ExampleRequest`. The following
   annotations are supported:
   ```monotone
   /**
   @_description - endpoint description
   @parameter-name - description of the input parameters specified in rules().
   Dot notation arrays will be automatically converted to the correct format
   */
   ```
   
4. Create tests that will test api through requests and check responses from
   the server. Documentation will be generated for requests and responses, so
   ideally you need to test all kinds of responses from the server for each
   endpoint. You may need to resort to using mocks to emit erroneous responses
   (e.g. 500)

5. Create a class anywhere in your project that inherits from
   `Bulbadev/Autodoc/ApiVersions/Base` and implement the required methods.
   An example can be found in`Bulbadev/Autodoc/ApiVersions/Example`

6. Specify in the config 'autodoc' all API classes for which you want
   to generate documentation
   ```monotone
   'api_versions' => [
        \App\ApiVersions\MyApiV1::class,
        \App\ApiVersions\MyApiV2::class,
    ],
   ```

7. Run the command to generate documentation. The parameter is not required,
   by default is the path specified in your class inherited from
   `Bulbadev/Autodoc/ApiVersions/Base`
   ```shell
   php artisan autodoc:generate 'tests/ApiTest.php --filter=testFirst'
   ```
   
8. The documentation generator has several modes:
   > **Force** - will generate endpoints AGAIN or create a new document
   > 
   > **UpdateOld** - whatever tests are run, it will only update those that have already been
   > 
   > **AddNew** - whatever tests are run, it will only add new endpoints
   > 
   > **NewOrUpdate** - updates old ones or creates new ones without deleting the rest
   
9. Create a route and controller with methods for displaying documentation
   and for getting a json file with documentation. An example controller can be found in
   `Bulbadev/Autodoc/Controllers/ExampleController`

<!-- LICENSE -->
## License

Distributed under the MIT License. See `LICENSE` for more information.

<!-- MARKDOWN LINKS & IMAGES -->
<!-- https://www.markdownguide.org/basic-syntax/#reference-style-links -->

[contributors-shield]: https://img.shields.io/github/contributors/Bulbadev/autodoc.svg?style=for-the-badge

[contributors-url]: https://github.com/Bulbadev/autodoc/graphs/contributors

[forks-shield]: https://img.shields.io/github/forks/Bulbadev/autodoc.svg?style=for-the-badge

[forks-url]: https://github.com/Bulbadev/autodoc/network/members

[stars-shield]: https://img.shields.io/github/stars/Bulbadev/autodoc.svg?style=for-the-badge

[stars-url]: https://github.com/Bulbadev/autodoc/stargazers

[issues-shield]: https://img.shields.io/github/issues/Bulbadev/autodoc.svg?style=for-the-badge

[issues-url]: https://github.com/Bulbadev/autodoc/issues

[linkedin-shield]: https://img.shields.io/badge/-LinkedIn-black.svg?style=for-the-badge&logo=linkedin&colorB=555

[linkedin-url]: https://www.linkedin.com/in/eugenekozlov/

[OAS-3.0.3]: https://swagger.io/specification/
