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

/* partials/widgets/filter_slide.twig */
class __TwigTemplate_330304447d57798f4b8d43c5185b2831 extends Template
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
        yield "<div class=\"main-search-container\"
     style=\"min-height: 80vh; background-color: #879ceb; background-size: cover; background-position: center; background-repeat: no-repeat;
             background-image: url('";
        // line 3
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->baseUrl(), "html", null, true);
        yield "/";
        yield (( !Twig\Extension\CoreExtension::testEmpty(CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "banner_link", [], "any", false, false, false, 3))) ? ($this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "banner_link", [], "any", false, false, false, 3), "html", null, true)) : ("images/banners/main_banner.png"));
        yield "');\">
    <div class=\"main-search-inner\">
        <div class=\"container\">
            <div class=\"row text-center\">
                <div class=\"col-md-12\">
                    <a class=\"button btn btn-purple text-white text-decoration-none\" href=\"";
        // line 8
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("registration"), "html", null, true);
        yield "\"
                       type=\"submit\" style=\"font-size: 20px; padding: 12px 30px; margin-top: 220px;\">
                        <i class=\"fa fa-arrow-right\"></i>
                        10 DAYS FREE TRIAL
                    </a>
                </div>
            </div>
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
        return "partials/widgets/filter_slide.twig";
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
        return array (  56 => 8,  46 => 3,  42 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "partials/widgets/filter_slide.twig", "C:\\xampp\\htdocs\\bazichic\\resources\\Views\\partials\\widgets\\filter_slide.twig");
    }
}
