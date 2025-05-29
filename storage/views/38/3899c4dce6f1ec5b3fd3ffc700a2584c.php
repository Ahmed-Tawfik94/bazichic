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

/* unauthorized.twig */
class __TwigTemplate_f5d5f9db1d4006c0d531693211dd34da extends Template
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
            'content' => [$this, 'block_content'],
        ];
    }

    protected function doGetParent(array $context): bool|string|Template|TemplateWrapper
    {
        // line 2
        return $this->loadTemplate(($context["layout_template"] ?? null), "unauthorized.twig", 2);
    }

    protected function doDisplay(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 1
        $context["layout_template"] = (((CoreExtension::getAttribute($this->env, $this->source, ($context["session"] ?? null), "userID", [], "any", false, false, false, 1) == 1)) ? ("admin_layout.twig") : ("layout.twig"));
        // line 2
        yield from $this->getParent($context)->unwrap()->yield($context, array_merge($this->blocks, $blocks));
    }

    // line 4
    /**
     * @return iterable<null|scalar|\Stringable>
     */
    public function block_content(array $context, array $blocks = []): iterable
    {
        $macros = $this->macros;
        // line 5
        yield "    <div class=\"container\">
        <div class=\"row\">
            <div class=\"col-md-12\">
                <section class=\"center\" style=\"text-align:center;margin:25px;padding:30px;background-color:#eee;\">
                    <h1 style=\"color:#666;font-size:72px;line-height:75px;font-weight:bold;\">
                        <i class=\"sl sl-icon-lock sl-3x\"></i>
                    </h1>
                    <p><strong>Looks like you are not authorized to access this page.</strong></p>
                    <hr>
                    <div class=\"row justify-content-center align-items-center\" style=\"margin:20px;\">
                        <div class=\"col-lg-8 col-lg-offset-2\">
                            ";
        // line 16
        if ((CoreExtension::getAttribute($this->env, $this->source, ($context["session"] ?? null), "userID", [], "any", false, false, false, 16) == 1)) {
            // line 17
            yield "                                <a href=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("home"), "html", null, true);
            yield "\" class=\"button btn btn-purple text-white\">
                                    <i class=\"sl sl-icon-home\"></i> Go to Home page
                                </a>
                                <a href=\"";
            // line 20
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("admin-panel"), "html", null, true);
            yield "\" class=\"button btn btn-purple text-white\">
                                    <i class=\"sl sl-icon-rocket\"></i> My Dashboard
                                </a>
                            ";
        } else {
            // line 24
            yield "                                <a href=\"";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("home"), "html", null, true);
            yield "\" class=\"button btn btn-purple text-white\">
                                    <i class=\"sl sl-icon-home\"></i> Go to Home page
                                </a>
                                <a href=\"";
            // line 27
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("dashboard"), "html", null, true);
            yield "\" class=\"button btn btn-purple text-white\">
                                    <i class=\"sl sl-icon-rocket\"></i> My Dashboard
                                </a>
                            ";
        }
        // line 31
        yield "                        </div>
                    </div>
                </section>
            </div>
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
        return "unauthorized.twig";
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
        return array (  103 => 31,  96 => 27,  89 => 24,  82 => 20,  75 => 17,  73 => 16,  60 => 5,  53 => 4,  49 => 2,  47 => 1,  40 => 2,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "unauthorized.twig", "C:\\xampp\\htdocs\\bazichic\\resources\\Views\\unauthorized.twig");
    }
}
