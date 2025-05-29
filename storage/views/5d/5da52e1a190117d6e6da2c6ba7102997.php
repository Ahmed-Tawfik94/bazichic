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

/* register.twig */
class __TwigTemplate_7d645d99cc052ed318efd206db6e84aa extends Template
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
        $this->parent = $this->loadTemplate("layout.twig", "register.twig", 1);
        yield from $this->parent->unwrap()->yield($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_slider(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        yield " 
";
        yield from [];
    }

    // line 5
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_content(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 6
        yield "<div class=\"container\">
<div class=\"row margin-top-10\">
<div class=\"col-lg-4\">
<div class=\"boxed-widget\" style=\"z-index: 50;box-sizing: border-box;border-radius: 4px;margin:15px;\">
<div class=\"text-center\">
<!-- Headline -->
<div class=\"inner\">
<div class=\"\"><img src=\"";
        // line 13
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/images/logo3.png\" style=\"width:140px;height:140px;margin:6px;\"></div>
<div class=\"text-center\">
<h3 style=\"color:#333;font-weight: bold;\">Welcome to Bazichic</h3>
<p style=\"color:#444;font-size: 15px;font-weight: bold;\">Chinese Metaphysics Consultancy</p>
</div>
<span style=\"color:#333;\">Join our community of like minded people to access knowledge and earn reward points when you learn.</span></div>
<a href=\"";
        // line 19
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("login"), "html", null, true);
        yield "\" class=\"button btn btn-purple text-white fullwidth margin-top-25\">
\t<i class=\"fa fa-user\"></i>
\tAlready a member?</a>

</div></div></div>
\t\t
<div class=\"col-lg-8\">
<div class=\"boxed-widget\" style=\"z-index: 50;box-sizing: border-box;border-radius: 4px;margin:15px;\">
";
        // line 27
        yield from $this->loadTemplate("forms/register_form.twig", "register.twig", 27)->unwrap()->yield($context);
        // line 28
        yield "</div>
</div>
<div class=\"col-lg-2\"></div>
</div>
</div>

";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "register.twig";
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
        return array (  102 => 28,  100 => 27,  89 => 19,  80 => 13,  71 => 6,  64 => 5,  52 => 3,  41 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "register.twig", "C:\\xampp\\htdocs\\bazichic\\resources\\Views\\register.twig");
    }
}
