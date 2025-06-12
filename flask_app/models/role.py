from flask_app.extensions import db  # Import db from extensions

class Role(db.Model):
    __tablename__ = 'roles'

    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.String(80), unique=True, nullable=False)

    # Relationship back to User is defined in User model using backref or secondary table
    # If using a simple ForeignKey in User:
    # users = db.relationship('User', back_populates='role')
    # If using many-to-many as currently in User model, backref 'users' is created there.

    def __repr__(self):
        return f'<Role {self.name}>'
