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

/* forms/login_form.twig */
class __TwigTemplate_070b590f71d2fba2f8f1e6f6e8c6d15b extends Template
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

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 1
        yield "<div id=\"login_overlay\" style=\"display:none;\">
\t<div style=\"margin: 30px; text-align: center;\">
\t\t<img src=\"";
        // line 3
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/images/preloader.gif\" width=\"60\"/>
\t\t<h4>
\t\t\t<strong>Authenticating...</strong>
\t\t</h4>
\t\t<h5>Please wait...</h5>
\t\t<div id=\"login_msg\"></div>
\t</div>
</div>

<form method=\"POST\" action=\"";
        // line 12
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("login_controller"), "html", null, true);
        yield "\" class=\"login\" name=\"loginForm\" id=\"loginForm\">
\t<div class=\"text-center\">

\t\t<p style=\"color:#333;font-weight:500;\">Signin to access thousands of E-Books, Audio Books and Magazines brought to you by BaziChic. Don't have an account yet!
\t\t\t<a href=\"";
        // line 16
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("register"), "html", null, true);
        yield "\" class=\"text-decoration-underline cursor-pointer\">Register here.</a>
\t\t</p>
\t</div>
\t<div class=\"form-group \">
\t\t<label for=\"email\">Email:</label>
\t\t<i class=\"im im-icon-Male position-absolute\"></i>
\t\t<input type=\"email\" class=\"form-control input-text position-relative\"
\t\t\t   id=\"email\"
\t\t\t   name=\"email\"
\t\t\t   pattern=\"^[A-Za-z0-9](([_\\.\\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([\\.\\-]?[a-zA-Z0-9]+)*)\\.([A-Za-z]{2,})\$\"
\t\t\t   placeholder=\"Your registered email\"
\t\t\t   required>
\t</div>
\t<div class=\"form-group \">
\t\t<label for=\"password\">Password:</label>
\t\t<i class=\"im im-icon-Lock-2 position-absolute\"></i>
\t\t<input type=\"password\" class=\"form-control input-text position-relative\" id=\"password\" name=\"password\" placeholder=\"Your Password\" required>
\t</div>
\t<div class=\"form-group\">
\t\t<a href=\"";
        // line 35
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("password-recovery"), "html", null, true);
        yield "\" class=\"text-decoration-none cursor-pointer\">
\t\t\tPassword Recovery
\t\t</a>
\t</div>

\t\t<div class=\"d-flex justify-content-center\">
\t\t<button type=\"submit\" class=\"button btn btn-purple text-white\">
\t\t\t<i class=\"fa fa-unlock-alt\"></i>
\t\t\tLog In
\t\t</button>
\t\t</div>

</form>
";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "forms/login_form.twig";
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
        return array (  87 => 35,  65 => 16,  58 => 12,  46 => 3,  42 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "forms/login_form.twig", "C:\\xampp\\htdocs\\bazichic\\resources\\Views\\forms\\login_form.twig");
    }
}
