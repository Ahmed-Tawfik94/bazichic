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

/* admin/partials/dash_nav.twig */
class __TwigTemplate_b6ae1caa3b6c4557ff900eb553f36fdd extends Template
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
        yield "<div class=\"dashboard-nav\">
    <a  href=\"#\" class=\"dashboard-responsive-nav-trigger\">
        <i class=\"fa fa-reorder\"></i>
        Dashboard Navigation</a>
    <div class=\"dashboard-nav-inner\">
            <ul data-submenu-title=\"Admin Panel\">
                <li ";
        // line 7
        if ((CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "name", [], "any", false, false, false, 7) == "adminpanel")) {
            yield " class=\"active\" ";
        }
        yield ">
                    <a class=\"text-decoration-none\" href=\"";
        // line 8
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("admin-panel"), "html", null, true);
        yield "\">
                        <i class=\"sl sl-icon-rocket\"></i>
                        Admin Panel</a>
                </li>

                <li ";
        // line 13
        if ((CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "name", [], "any", false, false, false, 13) == "settings")) {
            yield " class=\"active\" ";
        }
        yield ">
                    <a class=\"text-decoration-none\" href=\"";
        // line 14
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("configuration"), "html", null, true);
        yield "\">
                        <i class=\"sl sl-icon-settings\"></i>
                        Site Settings</a>
                </li>
                 <li ";
        // line 18
        if ((CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "name", [], "any", false, false, false, 18) == "notification")) {
            yield "class=\"active\"";
        }
        yield "><a class=\"text-decoration-none\" href=\"";
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("notifications"), "html", null, true);
        yield "\"><i class=\"sl sl-icon-bubbles\"></i> Notification</a></li>
                <li ";
        // line 19
        if ((CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "name", [], "any", false, false, false, 19) == "manage-documents")) {
            yield " class=\"active\" ";
        }
        yield ">
                    <a>
                        <i class=\"sl sl-icon-layers\"></i>
                        Manage Documents</a>
                    <ul>
                        <li><a class=\"text-decoration-none\" href=\"";
        // line 24
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("manage-documents"), "html", null, true);
        yield "\"><i class=\"sl sl-icon-plus\"></i>View All
                                Documents</a></li>
                        <li><a class=\"text-decoration-none\" href=\"";
        // line 26
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("manage-categories"), "html", null, true);
        yield "\"><i class=\"sl sl-icon-tag\"></i>Manage
                                Categories</a></li>
                        <li><a class=\"text-decoration-none\" href=\"";
        // line 28
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("manage-reviews"), "html", null, true);
        yield "\"><i class=\"sl sl-icon-bubbles\"></i>All Reviews</a>
                        </li>
                    </ul>
                </li>

                <li ";
        // line 33
        if ((CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "name", [], "any", false, false, false, 33) == "manage-membership")) {
            yield " class=\"active\" ";
        }
        yield ">
                    <a>
                        <i class=\"sl sl-icon-shield\"></i>
                        Manage Memberships</a>
                    <ul>
                        <li><a class=\"text-decoration-none\" href=\"";
        // line 38
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("manage-membership-plans"), "html", null, true);
        yield "\"><i
                                        class=\"sl sl-icon-badge\"></i>Membership Plans</a></li>
                        <li><a class=\"text-decoration-none\" href=\"";
        // line 40
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("manage-all-subscriptions"), "html", null, true);
        yield "\"><i class=\"sl sl-icon-wallet\"></i>Subscriptions</a>
                        </li>
                        <li><a class=\"text-decoration-none\" href=\"";
        // line 42
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("free-trials-summary"), "html", null, true);
        yield "\"><i class=\"sl sl-icon-plane\"></i>View Free
                                Trials</a></li>
                    </ul>
                </li>
                <li ";
        // line 46
        if ((CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "name", [], "any", false, false, false, 46) == "manage-users")) {
            yield " class=\"active\" ";
        }
        yield ">
                    <a>
                        <i class=\"bi bi-people\"></i>
                        Manage Users</a>
                    <ul>
                        <li><a class=\"text-decoration-none\" href=\"";
        // line 51
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("manage-users"), "html", null, true);
        yield "\"><i class=\"bi bi-people\"></i>View All Users</a>
                        </li>
                        <li><a class=\"text-decoration-none\" href=\"";
        // line 53
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("create-new-account"), "html", null, true);
        yield "\"><i class=\"sl sl-icon-plus\"></i>Add New
                                User</a></li>
                        <li><a class=\"text-decoration-none\" href=\"";
        // line 55
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("contact-form-submissions"), "html", null, true);
        yield "\"><i class=\"bi bi-envelope-arrow-up\"></i>Messages</a></li>
                    </ul>
                </li>

                <li ";
        // line 59
        if ((CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "name", [], "any", false, false, false, 59) == "manage-faqs")) {
            yield " class=\"active\" ";
        }
        yield ">
                    <a>
                        <i class=\"sl sl-icon-pin\"></i>
                        Manage FAQs</a>
                    <ul>
                        <li><a class=\"text-decoration-none\" href=\"";
        // line 64
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("manage-faqs"), "html", null, true);
        yield "\"><i class=\"sl sl-icon-pin\"></i>View All FAQs 2</a></li>
                        <li><a class=\"text-decoration-none\" href=\"";
        // line 65
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("add-faq"), "html", null, true);
        yield "\"><i class=\"sl sl-icon-plus\"></i>Add New FAQ</a></li>
                        <li><a class=\"text-decoration-none\" href=\"";
        // line 66
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("faqs-category-manager"), "html", null, true);
        yield "\"><i class=\"sl sl-icon-list\"></i>FAQCategorization</a></li>
                    </ul>
                </li>
            </ul>
        <ul data-submenu-title=\"Account\">
            <li ";
        // line 71
        if ((CoreExtension::getAttribute($this->env, $this->source, ($context["page"] ?? null), "name", [], "any", false, false, false, 71) == "profile")) {
            yield " class=\"active\" ";
        }
        yield ">

                <a class=\"text-decoration-none\" href=\"";
        // line 73
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("admin-profile"), "html", null, true);
        yield "\">
                    <i class=\"sl sl-icon-user\"></i>
                    My Profile</a>
            </li>
            <li>
                <a class=\"text-decoration-none\" href=\"";
        // line 78
        yield $this->env->getRuntime('Twig\Runtime\EscaperRuntime')->escape($this->extensions['Slim\Views\TwigExtension']->fullUrlFor("logout"), "html", null, true);
        yield "\">
                    <i class=\"sl sl-icon-power\"></i>Logout</a>
            </li>
        </ul>

    </div>
</div>
<!-- Navigation / End -->
";
        yield from [];
    }

    /**
     * @codeCoverageIgnore
     */
    public function getTemplateName(): string
    {
        return "admin/partials/dash_nav.twig";
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
        return array (  208 => 78,  200 => 73,  193 => 71,  185 => 66,  181 => 65,  177 => 64,  167 => 59,  160 => 55,  155 => 53,  150 => 51,  140 => 46,  133 => 42,  128 => 40,  123 => 38,  113 => 33,  105 => 28,  100 => 26,  95 => 24,  85 => 19,  77 => 18,  70 => 14,  64 => 13,  56 => 8,  50 => 7,  42 => 1,);
    }

    public function getSourceContext(): Source
    {
        return new Source("", "admin/partials/dash_nav.twig", "C:\\xampp\\htdocs\\bazichic\\resources\\Views\\admin\\partials\\dash_nav.twig");
    }
}
