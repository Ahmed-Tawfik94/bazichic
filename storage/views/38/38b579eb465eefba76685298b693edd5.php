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

/* partials/widgets/membership_plan.twig */
class __TwigTemplate_ddce948b57ce8b2480f9d24e9930468c extends Template
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
        yield "<div class=\"container\">


    <div class=\"row\">
        <div class=\"col-md-12\">
            <h3 class=\"headline centered margin-top-75\">
                Membership Plans
                <span>10 Days Free Trial for all plans.</span>
            </h3>
        </div>
    </div>

    <!-- Row / Start -->
    <section id=\"pricing\" class=\"pricing-content section-padding\">
        <div class=\"container\">
            <div class=\"row text-center\">

                ";
        // line 18
        yield from $this->loadTemplate("partials/widgets/membership-table.twig", "partials/widgets/membership_plan.twig", 18)->unwrap()->yield($context);
        // line 19
        yield "
            </div>
        </div>
    </section>
    <!-- Row / End -->

</div>";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "partials/widgets/membership_plan.twig";
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
        return array (  63 => 19,  61 => 18,  42 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "partials/widgets/membership_plan.twig", "C:\\xampp\\htdocs\\bazichic\\resources\\Views\\partials\\widgets\\membership_plan.twig");
    }
}
