<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\CoreExtension;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;
use Twig\TemplateWrapper;

/* login.twig */
class __TwigTemplate_7ba182c355814d2d787ef969df5dbbe3 extends Template
{
    private Source $source;
    /**
     * @var array<string, Template>
     */
    private array $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->blocks = [
            'slider' => [$this, 'block_slider'],
            'content' => [$this, 'block_content'],
        ];
    }

    protected function doGetParent(array $context): bool|string|Template|TemplateWrapper
    {
        // line 1
        return "layout.twig";
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        $this->parent = $this->loadTemplate("layout.twig", "login.twig", 1);
        yield from $this->parent->unwrap()->yield($context, array_merge($this->blocks, $blocks));
    }

    // line 2
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_slider(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        yield from [];
    }

    // line 3
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_content(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 4
        yield "\t<div class=\"container\">
\t\t<div class=\"row\">
\t\t\t<div class=\"col-12 col-md-6\">
\t\t\t\t<div class=\"boxed-widget mx-auto\" style=\"padding:30px; z-index:50; box-sizing: border-box; border-radius:4px; margin:15px;\">

\t\t\t\t\t<div id=\"login_page_overlay\" style=\"display:none;\">
\t\t\t\t\t\t<div style=\"margin: 60px; text-align: center;\">
\t\t\t\t\t\t\t<img src=\"";
        // line 11
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/images/preloader.gif\" width=\"50\" alt=\"preloader\"/>
\t\t\t\t\t\t\t<h4>
\t\t\t\t\t\t\t\t<strong>Authenticating...</strong>
\t\t\t\t\t\t\t</h4>
\t\t\t\t\t\t\t<h5>Please wait...</h5>
\t\t\t\t\t\t\t<div id=\"login_page_msg\"></div>
\t\t\t\t\t\t</div>
\t\t\t\t\t</div>

\t\t\t\t\t<form method=\"POST\" action=\"";
        // line 20
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("login_controller"), "html", null, true);
        yield "\" class=\"login\" name=\"loginPageForm\" id=\"loginPageForm\">
\t\t\t\t\t\t<div class=\"text-center\">
\t\t\t\t\t\t\t<h3>Member Login</h3>
\t\t\t\t\t\t</div>
\t\t\t\t\t\t<div class=\"text-center\">
\t\t\t\t\t\t\t<p class=\"text-muted\">Sign in to access thousands of E-Books, Audio Books, and Magazines brought to you by BaziChic.</p>
\t\t\t\t\t\t</div>
\t\t\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t\t\t<i class=\"im im-icon-Male\"></i>
\t\t\t\t\t\t\t<label for=\"email\">Email Address</label>
\t\t\t\t\t\t\t<input type=\"email\" class=\"form-control input-text\" id=\"email\" name=\"email\" aria-describedby=\"emailHelp\" placeholder=\"Your registered email\" required>
\t\t\t\t\t\t</div>
\t\t\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t\t\t<i class=\"im im-icon-Lock-2\"></i>
\t\t\t\t\t\t\t<label for=\"password\">Password</label>
\t\t\t\t\t\t\t<input type=\"password\" class=\"form-control input-text position-relative\" id=\"password\" name=\"password\" placeholder=\"Your Password\" required>
\t\t\t\t\t\t</div>
\t\t\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t\t\t<a href=\"";
        // line 38
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("password-recovery"), "html", null, true);
        yield "\" class=\"text-decoration-underline cursor-pointer\">
\t\t\t\t\t\t\t\tRecover Password
\t\t\t\t\t\t\t</a>
\t\t\t\t\t\t</div>
\t\t\t\t\t\t<div class=\"form-group mb-0 text-center\">
\t\t\t\t\t\t\t<button type=\"submit\" class=\"btn btn-success login-page-button text-decoration-none text-white\">
\t\t\t\t\t\t\t\t<i class=\"fa fa-unlock-alt\"></i>
\t\t\t\t\t\t\t\tLog In
\t\t\t\t\t\t\t</button>
\t\t\t\t\t\t</div>
\t\t\t\t\t</form>
\t\t\t\t</div>
\t\t\t</div>

\t\t\t<div class=\"col-12 col-md-6 d-flex\">
\t\t\t\t<div class=\"boxed-widget flex-fill mx-auto\" style=\"z-index: 50; box-sizing: border-box; border-radius: 4px; margin:15px;\">
\t\t\t\t\t<div
\t\t\t\t\t\tclass=\"text-center d-flex flex-column justify-content-between h-100\">
\t\t\t\t\t\t<!-- Headline -->
\t\t\t\t\t\t<div class=\"text-center\">
\t\t\t\t\t\t\t<h3 style=\"color:#333;\">Create Account</h3>
\t\t\t\t\t\t</div>
\t\t\t\t\t\t<div
\t\t\t\t\t\t\tclass=\"inner\">
\t\t\t\t\t\t\t";
        // line 63
        yield "\t\t\t\t\t\t\t<span style=\"color:#333;\">Are you a registered subscriber of BaziChic? Sign in using your credentials to access unlimited E-Books, Audio Books and Magazines.</span>
\t\t\t\t\t\t</div>
\t\t\t\t\t\t<div class=\"form-group mb-0 text-center\">

\t\t\t\t\t\t\t<a href=\"";
        // line 67
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("register"), "html", null, true);
        yield "\" class=\"btn btn-success login-page-button text-decoration-none text-white\">

\t\t\t\t\t\t\t\t<i class=\"fa fa-user\"></i>
\t\t\t\t\t\t\t\tRegister New Account
\t\t\t\t\t\t\t</a>
\t\t\t\t\t\t</div>
\t\t\t\t\t</div>
\t\t\t\t</div>
\t\t\t</div>
\t\t</div>
\t</div>
\t<style>
\t\t.login-page-button {
\t\t\tdisplay: inline-block; /* Make the button fit its content */
\t\t\tmin-width: 250px; /* Minimum width for buttons */
\t\t\ttext-align: center; /* Center the text and icons */
\t\t\tpadding: 0.5rem 1rem; /* Add padding for content spacing */
\t\t}

\t\t@media(max-width: 768px) {
\t\t\t/* Full width on smaller screens */
\t\t\t.login-page-button {
\t\t\t    min-width: 0px; /* Minimum width for buttons */
\t\t\t\tdisplay: block; /* Make buttons block elements */
\t\t\t\twidth: 100%; /* Full width */
\t\t\t\tmargin: 0 auto; /* Center align */
\t\t\t}
\t\t}
\t</style>
";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "login.twig";
    }

    /**
     * @codeCoverageIgnore
     */
    public function isTraitable(): bool
    {
        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getDebugInfo(): array
    {
        return array (  144 => 67,  138 => 63,  111 => 38,  90 => 20,  78 => 11,  69 => 4,  62 => 3,  52 => 2,  41 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "login.twig", "C:\\xampp\\htdocs\\bazichic\\resources\\Views\\login.twig");
    }
}
