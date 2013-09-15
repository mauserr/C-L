C-L
===

Cenários e Léxicos - Uma ferramenta de edição de Cenários e Léxico disponibilizada em pes.inf.puc-rio.br/cel.


Part 1. Design Patterns

**************************************************************************
*                                                                        *
*                               C&L                                      *
*                        "Cenário & Léxicos"                             *
*                                                                        *
* Was initially developed as an academic and now evolves into a Free     *
* Software project. The project is from the Group on Requirements        *
* Engineering, from PUC-RIO.                                             *
*                                                                        *
* The goal of this project is to study and analyze software evolution    *
* techniques through a practical experiment.                             *
*                                                                        *
* The application used as an initial project to be 'evolved'             *
* was a tool for editing and Lexicon Scenarios available in              *
* feet http://springfield.genesis.puc-rio.br:81/ ~ /.                    *                                         
*                                                                        *
* This Document describes the desing patters adopted in this copy of the *
* project. The following team is responsible for adapting the project    *
* and refactor the code:                                                 *
*                                                                        *
*                     Ana Paula Vargas                                   *
*                     Alex Cortes Alves                                  *
*                     Fillipe Oliveira Feitosa                           *
*                     Wilker Mesquita                                    *
*                                                                        *
* Under the guidence of prof. Maurício Serrano, Universidade de Brasília *
*                              FGA                                       *
*                                                                        *
**************************************************************************


@ Adopted Patterns

1. Language

	The language adopted for documentations, functions and variables
	is the english.

2. Style and Desing
	
	2.1. Variables
	Variables must be declared with a name that corresponds exactly 
	with its goals. If the variable must have two or more names, use
	a underscore to separate them. The first word must initiate with
	low case.
	ex.: boolean check_User = false;
	Obs.: For laces and loopes that need temporary variables, use i, 
	j, k, l and etc.

	2.2.Identation
	The identation must be use correctly in all files, classes, 
	funtions and all types os declarations. 

3. Clarity of Code

	Prioritize clarity rather than brevity. Keep the coding simple.
	Do not make long functions. Use your good judgment and create 
	functions that can be self explanatory.


4. Static Analisys 
	
	The software Static Analisys chosen for our project is RIPS,
	Available at http://rips-scanner.sourceforge.net/

	4.1. Error and Warning
	All errors and warnings generated in RIPS must be corretly fixed.
	
5. Apache Warnings
	
	All warnings in apache must be activated, and must be corrected 
	to prevent malfunction.

6. Return Values

	It is imperative that all return values be tested. 

7. Variables

	7.1. Initializing Variavles
	All variables must be initialized at declaration. Avoid Declaring
	global variables and try to declared them the later possible.

	ex.: int user_Number = 3;

	7.2. Declare constants
	Whenever is possible and needed, use constants to values that will 
	not be changed.

	7.3. Comments
	When will not be cristal clear what a variable or a constant will do
	create a small comment explaining its use and meaning.	

8. If´s and Switches
	
	8.1. Default Behavior
	When using If´s and switches, always configure the default behavior, 
	even if it is a condition that will break the program, or will fail
	somehow. If nothing is going to happen, make it clear with a commentary.

	8.2. If declaration and identation
	Always use the open and close keys as the exemple above:
	ex.:
	
	if("condition"){
		//some idented code here
	} 	
	
	8.3. Switch declaration and identation
	Always use switch like showed as above, using break when needed:

	ex.:
	
	switch("parameter"){
		case 0:
		//some idented code here
		break;
		
		case 1:
		//some idented code here
		break;
		
		case 3:
		//nothing to do here
		break;
	}

9. Numeric Limits
	
	9.1. Arrays 
	Always specify and check the numeric limits of an array, to add an
	element, or to work with the array.

	9.2. Unsined
	DO NOT use unsigned variables.

10. Assertives (Not Supported Yet)
	
	10.1. Check Entries
	Always check all the entries to all functions. Before execute the 
	function, test if the entries are really what they are expected, 
	including type, size and values.
	
	10.2. Error Messages
	If any kind of problem is find while checking the entry values,
	create a message routine to the user, or finish the code exibition.


Brasília, DF. Brazil.


















