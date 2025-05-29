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

/* partials/user_dashboard.twig */
class __TwigTemplate_0fe94183201b12bc2b9cb384b65ca70b extends Template
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
        yield "<div class=\"dashboard-nav d-none d-md-block\">
    <a href=\"#\" class=\"dashboard-responsive-nav-trigger\">
        <i class=\"fa fa-reorder\"></i>
        Dashboard Navigation
    </a>
    <div class=\"dashboard-nav-inner\">
        <ul data-submenu-title=\"Welcome ";
        // line 7
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["session"] ?? null), "first_name", [], "any", false, false, false, 7), "html", null, true);
        yield "!\">
            <li ";
        // line 8
        if ((CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "name", [], "any", false, false, false, 8) == "dashboard")) {
            yield " class=\"active\" ";
        }
        yield ">
                <a class=\"text-decoration-none\" href=\"";
        // line 9
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("dashboard"), "html", null, true);
        yield "\">
                    <i class=\"sl sl-icon-rocket\"></i>
                    My Dashboard
                </a>
            </li>
            <li ";
        // line 14
        if ((CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "name", [], "any", false, false, false, 14) == "notification")) {
            yield " class=\"active\" ";
        }
        yield ">
                <a class=\"text-decoration-none\" href=\"";
        // line 15
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("my_notifications"), "html", null, true);
        yield "\">
                    <i class=\"bi bi-bell\"></i>
                    Notifications
                </a>
            </li>
            <li ";
        // line 20
        if ((CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "name", [], "any", false, false, false, 20) == "referrals")) {
            yield " class=\"active\" ";
        }
        yield ">
                <a class=\"text-decoration-none\" href=\"";
        // line 21
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("referral-codes"), "html", null, true);
        yield "\">
                    <i class=\"sl sl-icon-rocket\"></i>
                    Refer & Earn
                </a>
            </li>
            <li ";
        // line 26
        if ((CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "name", [], "any", false, false, false, 26) == "savedreads")) {
            yield " class=\"active\" ";
        }
        yield ">
                <a class=\"text-decoration-none\" href=\"";
        // line 27
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("saved-reads"), "html", null, true);
        yield "\">
                    <i class=\"sl im im-icon-Arrow-LeftinCircle\"></i>
                    Saved Reads
                    ";
        // line 30
        if (CoreExtension::getAttribute($this->env, $this->source, ($context["session"] ?? null), "num_saves", [], "any", false, false, false, 30)) {
            // line 31
            yield "                        <span class=\"nav-tag messages\">";
            yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape(CoreExtension::getAttribute($this->env, $this->source, ($context["session"] ?? null), "num_saves", [], "any", false, false, false, 31), "html", null, true);
            yield "</span>
                    ";
        }
        // line 33
        yield "                </a>
            </li>
            <li ";
        // line 35
        if ((CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "name", [], "any", false, false, false, 35) == "favourites")) {
            yield " class=\"active\" ";
        }
        yield ">
                <a class=\"text-decoration-none\" href=\"";
        // line 36
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("bookmarks"), "html", null, true);
        yield "\">
                    <i class=\"sl sl-icon-heart\"></i>
                    My Favourites
                </a>
            </li>
        </ul>

        <ul data-submenu-title=\"Account\">
            <li ";
        // line 44
        if ((CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "name", [], "any", false, false, false, 44) == "profile")) {
            yield " class=\"active\" ";
        }
        yield ">
                <a class=\"text-decoration-none\" href=\"";
        // line 45
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("my-profile"), "html", null, true);
        yield "\">
                    <i class=\"sl sl-icon-user\"></i>
                    My Profile
                </a>
            </li>
            <li ";
        // line 50
        if ((CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "name", [], "any", false, false, false, 50) == "my-membership")) {
            yield " class=\"active\" ";
        }
        yield ">
                <a class=\"text-decoration-none\" href=\"";
        // line 51
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("my-subscriptions"), "html", null, true);
        yield "\">
                    <i class=\"sl sl-icon-shield\"></i>
                    Membership
                </a>
            </li>
            <li>
                <a class=\"text-decoration-none\" href=\"";
        // line 57
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("logout"), "html", null, true);
        yield "\">
                    <i class=\"sl sl-icon-power\"></i>Logout
                </a>
            </li>
        </ul>

        <!-- Referral Code Section -->

    </div>
</div>
<!-- Navigation / End -->

<!-- Add this script -->

";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "partials/user_dashboard.twig";
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
        return array (  166 => 57,  157 => 51,  151 => 50,  143 => 45,  137 => 44,  126 => 36,  120 => 35,  116 => 33,  110 => 31,  108 => 30,  102 => 27,  96 => 26,  88 => 21,  82 => 20,  74 => 15,  68 => 14,  60 => 9,  54 => 8,  50 => 7,  42 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "partials/user_dashboard.twig", "C:\\xampp\\htdocs\\bazichic\\resources\\Views\\partials\\user_dashboard.twig");
    }
}
