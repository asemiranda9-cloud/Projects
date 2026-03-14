import 'package:flutter/material.dart';
import 'package:flutter/services.dart'; 

class AccountFormScreen extends StatefulWidget {
  const AccountFormScreen({super.key});

  @override
  // ignore: library_private_types_in_public_api
  _AccountFormScreenState createState() => _AccountFormScreenState();
}

class _AccountFormScreenState extends State<AccountFormScreen> {
  final _formKey = GlobalKey<FormState>();

  // Controllers for form inputs
  final TextEditingController _idNumberController = TextEditingController();
  final TextEditingController _firstNameController = TextEditingController();
  final TextEditingController _middleNameController = TextEditingController();
  final TextEditingController _lastNameController = TextEditingController();
  final TextEditingController _birthDateController = TextEditingController(); // For DatePicker

  // For middle name checkbox
  bool _hasMiddleName = true;

  // ID Type - Dropdown value
  String? _idType;

  // Function to handle date picker (for birthday or date related inputs)
  Future<void> _selectDate(BuildContext context) async {
    final DateTime? picked = await showDatePicker(
      context: context,
      initialDate: DateTime.now(),
      firstDate: DateTime(1900),
      lastDate: DateTime.now(),
    );
    if (picked != null) {
      setState(() {
        _birthDateController.text =
            '${picked.year}-${picked.month.toString().padLeft(2, '0')}-${picked.day.toString().padLeft(2, '0')}';
      });
    }
  }

  // Method to navigate to the confirmation page
  void _goToConfirmationPage() {
    if (_formKey.currentState?.validate() ?? false) {
      // If form is valid, pass the data to the confirmation page
      Navigator.push(
        context,
        MaterialPageRoute(
          builder: (context) => ConfirmationPage(
            idType: _idType ?? 'N/A',
            idNumber: _idNumberController.text,
            firstName: _firstNameController.text,
            middleName: _hasMiddleName ? _middleNameController.text : 'N/A',
            lastName: _lastNameController.text,
            birthDate: _birthDateController.text,
          ),
        ),
      );
    }
  }

  // Custom decoration
  InputDecoration _customDecoration(String label) {
    return InputDecoration(
      labelText: label,
      labelStyle: TextStyle(color: Colors.black),
      fillColor: const Color.fromARGB(255, 246, 245, 245),
      border: OutlineInputBorder(
        borderRadius: BorderRadius.circular(12),
        borderSide: BorderSide.none,
      ),
      filled: true,
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Back'),
        backgroundColor: const Color.fromARGB(255, 255, 255, 255),
      ),
      body: Padding(
        padding: EdgeInsets.all(16.0),
        child: Form(
          key: _formKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: <Widget>[
              Text(
                'Please complete the information below.',
                style: TextStyle(fontSize: 16),
              ),
              SizedBox(height: 20),

              // ID Type - Dropdown
              DropdownButtonFormField<String>(
                initialValue: _idType,
                decoration: _customDecoration('ID Type'),
                items: [
                  DropdownMenuItem(
                    value: 'PWD (Persons with Disabilities)',
                    child: Text('PWD (Persons with Disabilities)'),
                  ),
                  DropdownMenuItem(
                    value: 'Senior Citizen',
                    child: Text('Senior Citizen'),
                  ),
                ],
                onChanged: (value) {
                  setState(() {
                    _idType = value;
                  });
                },
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Please select an ID type';
                  }
                  return null;
                },
              ),
              SizedBox(height: 20),

              // ID Number Input (Only accepts integers)
              TextFormField(
                controller: _idNumberController,
                decoration: _customDecoration('ID Number'),
                keyboardType: TextInputType.number,
                inputFormatters: [
                  FilteringTextInputFormatter.digitsOnly, // Only digits allowed
                ],
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Please enter your ID number';
                  }
                  if (int.tryParse(value) == null) {
                    return 'ID Number must be an integer';
                  }
                  return null;
                },
              ),
              SizedBox(height: 20),

              // First Name Input
              TextFormField(
                controller: _firstNameController,
                decoration: _customDecoration('First Name'),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Please enter your first name';
                  }
                  return null;
                },
              ),
              SizedBox(height: 20),

              // Middle Name Input
              TextFormField(
                controller: _middleNameController,
                decoration: _customDecoration('Middle Name'),
                enabled: _hasMiddleName, // Disable the field if "No middle name" is checked
              ),
              Row(
                children: [
                  Checkbox(
                    value: !_hasMiddleName,
                    onChanged: (value) {
                      setState(() {
                        _hasMiddleName = !value!;
                        if (!_hasMiddleName) {
                          _middleNameController.clear(); // Clear the middle name if unchecked
                        }
                      });
                    },
                  ),
                  Text("I do not have a middle name."),
                ],
              ),
              SizedBox(height: 20),

              // Last Name Input
              TextFormField(
                controller: _lastNameController,
                decoration: _customDecoration('Last Name'),
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Please enter your last name';
                  }
                  return null;
                },
              ),
              SizedBox(height: 20),

              // Birthdate Input with DatePicker
              TextFormField(
                controller: _birthDateController,
                decoration: InputDecoration(
                  labelText: 'Birthdate',
                  labelStyle: TextStyle(color: Colors.black),
                  fillColor: const Color.fromARGB(255, 255, 255, 255),
                  border: OutlineInputBorder(
                    borderRadius: BorderRadius.circular(12),
                    borderSide: BorderSide.none,
                  ),
                  filled: true,
                  suffixIcon: IconButton(
                    icon: Icon(Icons.calendar_today),
                    onPressed: () => _selectDate(context),
                  ),
                ),
                readOnly: true, // Makes the field read-only (no typing)
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Please select your birthdate';
                  }
                  return null;
                },
              ),
              SizedBox(height: 20),

              // Next Button
              ElevatedButton(
                onPressed: _goToConfirmationPage,
                style: ElevatedButton.styleFrom(
                  backgroundColor: Colors.orange,
                  padding: EdgeInsets.symmetric(horizontal: 50, vertical: 15),
                ),
                child: Text(
                  'NEXT',
                  style: TextStyle(color: Colors.white),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

class ConfirmationPage extends StatelessWidget {
  final String idType;
  final String idNumber;
  final String firstName;
  final String middleName;
  final String lastName;
  final String birthDate;

  const ConfirmationPage({
    super.key,
    required this.idType,
    required this.idNumber,
    required this.firstName,
    required this.middleName,
    required this.lastName,
    required this.birthDate,
  });

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Confirm Your Information'),
        backgroundColor: const Color.fromARGB(255, 255, 255, 255),
      ),
      body: Padding(
        padding: EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: <Widget>[
            Text(
              'Please review your information before submitting.',
              style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
            ),
            SizedBox(height: 20),

            // Display the form data with larger text and bold font
            Text('ID Type: $idType', style: TextStyle(fontSize: 24, )),
            SizedBox(height: 10),
            Text('ID Number: $idNumber', style: TextStyle(fontSize: 24, )),
            SizedBox(height: 10),
            Text('First Name: $firstName', style: TextStyle(fontSize: 24, )),
            SizedBox(height: 10),
            Text('Middle Name: $middleName', style: TextStyle(fontSize: 24, )),
            SizedBox(height: 10),
            Text('Last Name: $lastName', style: TextStyle(fontSize: 24, )),
            SizedBox(height: 10),
            Text('Birthdate: $birthDate', style: TextStyle(fontSize: 24, )),

            SizedBox(height: 20),

            // Buttons for confirmation or editing
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceEvenly,
              children: [
                ElevatedButton(
                  onPressed: () {
                    // Here you can handle the submission of data, like sending to a server
                    ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text('Information Confirmed')));
                  },
                  style: ElevatedButton.styleFrom(
                    backgroundColor: const Color.fromARGB(255, 255, 255, 255),
                    padding: EdgeInsets.symmetric(horizontal: 50, vertical: 15),
                  ),
                  child: Text('Confirm', style: TextStyle(color: Colors.orange)),
                ),
                ElevatedButton(
                  onPressed: () {
                    // If the user wants to edit their form, navigate back
                    Navigator.pop(context);
                  },
                  style: ElevatedButton.styleFrom(
                    backgroundColor: Colors.orange,
                    padding: EdgeInsets.symmetric(horizontal: 50, vertical: 15),
                  ),
                  child: Text('Edit', style: TextStyle(color: Colors.white)),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }
}